<?php namespace Leertaak5\Helpers;

class ZipStream
{
    private $opt = [];
    private $files = [];
    private $cdr_ofs = 0;
    private $ofs = 0;
    private $needHeaders;
    public function __construct($name = null, $opt = array())
    {
        # save options
        $this->opt = $opt;

        # set large file defaults: size = 20 megabytes, method = store
        if (!isset($this->opt['largeFileSize'])) {
            $this->opt['largeFileSize'] = 20 * 1024 * 1024;
        }
        if (!isset($this->opt['largeFileMethod'])) {
            $this->opt['largeFileMethod'] = 'store';
        }

        $this->output_name = $name;
        if ($name || $opt['sendHttpHeaders']) {
            $this->needHeaders = true;
        }
    }

    public function addFileFromPath($name, $path, $opt = array())
    {
        if ($this->isLargeFile($path)) {
            # file is too large to be read into memory; add progressively
            $this->addLargeFile($name, $path, $opt);
        } else {
            # file is small enough to read into memory; read file contents and
            # handle with addFile()
            $data = file_get_contents($path);
            $this->addFile($name, $data, $opt);
        }
    }

    public function isLargeFile($path)
    {
        $st = stat($path);

        return ($this->opt['largeFileSize'] > 0) &&
        ($st['size'] > $this->opt['largeFileSize']);
    }

    private function addLargeFile($name, $path, $opt = array())
    {
        $st = stat($path);
        $block_size = 1048576; # process in 1 megabyte chunks
        $algo = 'crc32b';

        # calculate header attributes
        $zlen = $len = $st['size'];

        $meth_str = $this->opt['largeFileMethod'];
        if ($meth_str == 'store') {
            # store method
            $meth = 0x00;
            $crc = unpack('V', hash_file($algo, $path, true));
            $crc = $crc[1];
        } elseif ($meth_str == 'deflate') {
            # deflate method
            $meth = 0x08;

            # open file, calculate crc and compressed file length
            $fh = fopen($path, 'rb');
            $hash_ctx = hash_init($algo);
            $zlen = 0;

            # read each block, update crc and zlen
            while ($data = fgets($fh, $block_size)) {
                hash_update($hash_ctx, $data);
                $data = gzdeflate($data);
                $zlen += strlen($data);
            }

            # close file and finalize crc
            fclose($fh);
            $crc = unpack('V', hash_final($hash_ctx, true));
            $crc = $crc[1];
        } else {
            die("unknown large_file_method: $meth_str");
        }

        # send file header
        $this->addFileHeader($name, $opt, $meth, $crc, $zlen, $len);

        # open input file
        $fh = fopen($path, 'rb');

        # send file blocks
        while ($data = fgets($fh, $block_size)) {
            if ($meth_str == 'deflate') {
                $data = gzdeflate($data);
            }

            # send data
            $this->send($data);
        }

        # close input file
        fclose($fh);
    }

    ###################
    # PRIVATE METHODS #
    ###################

    #
    # Create and send zip header for this file.
    #

    private function addFileHeader($name, $opt, $meth, $crc, $zlen, $len)
    {
        # strip leading slashes from file name
        # (fixes bug in windows archive viewer)
        $name = preg_replace('/^\\/+/', '', $name);

        # calculate name length
        $nlen = strlen($name);

        # create dos timestamp
        $opt['time'] = isset($opt['time']) ? $opt['time'] : time();
        $dts = $this->dostime($opt['time']);

        # build file header
        $fields = array( # (from V.A of APPNOTE.TXT)
            array('V', 0x04034b50), # local file header signature
            array('v', (6 << 8) + 3), # version needed to extract
            array('v', 0x00), # general purpose bit flag
            array('v', $meth), # compresion method (deflate or store)
            array('V', $dts), # dos timestamp
            array('V', $crc), # crc32 of data
            array('V', $zlen), # compressed data length
            array('V', $len), # uncompressed data length
            array('v', $nlen), # filename length
            array('v', 0), # extra data len
        );

        # pack fields and calculate "total" length
        $ret = $this->packFields($fields);
        $cdr_len = strlen($ret) + $nlen + $zlen;

        # print header and filename
        $this->send($ret . $name);

        # add to central directory record and increment offset
        $this->addToCdr($name, $opt, $meth, $crc, $zlen, $len, $cdr_len);
    }

    #
    # Add a large file from the given path.
    #

    private function dostime($when = 0)
    {
        # get date array for timestamp
        $d = getdate($when);

        # set lower-bound on dates
        if ($d['year'] < 1980) {
            $d = array(
                'year' => 1980,
                'mon' => 1,
                'mday' => 1,
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0
            );
        }

        # remove extra years from 1980
        $d['year'] -= 1980;

        # return date string
        return ($d['year'] << 25) | ($d['mon'] << 21) | ($d['mday'] << 16) |
        ($d['hours'] << 11) | ($d['minutes'] << 5) | ($d['seconds'] >> 1);
    }

    #
    # Is this file larger than large_file_size?
    #

    private function packFields($fields)
    {
        list ($fmt, $args) = array('', array());

        # populate format string and argument list
        foreach ($fields as $field) {
            $fmt .= $field[0];
            $args[] = $field[1];
        }

        # prepend format string to argument list
        array_unshift($args, $fmt);

        # build output string from header and compressed data
        return call_user_func_array('pack', $args);
    }

    #
    # Save file attributes for trailing CDR record.
    #

    private function send($str)
    {
        if ($this->needHeaders) {
            $this->sendHttpHeaders();
        }
        $this->needHeaders = false;

        echo $str;
    }

    #
    # Send CDR record for specified file.
    #

    private function sendHttpHeaders()
    {
        # grab options
        $opt = $this->opt;

        # grab content type from options
        $contentType = 'application/x-zip';
        if (isset($opt['contentType'])) {
            $contentType = $this->opt['contentType'];
        }

        # grab content disposition
        $disposition = 'attachment';
        if (isset($opt['contentDisposition'])) {
            $disposition = $opt['contentDisposition'];
        }

        if ($this->output_name) {
            $disposition .= "; filename=\"{$this->output_name}\"";
        }

        $headers = array(
            'Content-Type' => $contentType,
            'Content-Disposition' => $disposition,
            'Pragma' => 'public',
            'Cache-Control' => 'public, must-revalidate',
            'Content-Transfer-Encoding' => 'binary',
        );

        foreach ($headers as $key => $val) {
            header("$key: $val");
        }
    }

    #
    # Send CDR EOF (Central Directory Record End-of-File) record.
    #

    private function addToCdr($name, $opt, $meth, $crc, $zlen, $len, $recLen)
    {
        $this->files[] = array($name, $opt, $meth, $crc, $zlen, $len, $this->ofs);
        $this->ofs += $recLen;
    }

    #
    # Add CDR (Central Directory Record) footer.
    #

    private function addFile($name, $data, $opt = array())
    {
        # compress data
        $zdata = gzdeflate($data);

        # calculate header attributes
        $crc = crc32($data);
        $zlen = strlen($zdata);
        $len = strlen($data);
        $meth = 0x08;

        # send file header
        $this->addFileHeader($name, $opt, $meth, $crc, $zlen, $len);

        # print data
        $this->send($zdata);
    }

    #
    # Clear all internal variables.  Note that the stream object is not
    # usable after this.
    #

    public function finish()
    {
        # add trailing cdr record
        $this->addCdr($this->opt);
        $this->clear();
    }

    ###########################
    # PRIVATE UTILITY METHODS #
    ###########################

    #
    # Send HTTP headers for this stream.
    #

    private function addCdr($opt = null)
    {
        foreach ($this->files as $file) {
            $this->addCdrFile($file);
        }
        $this->addCdrEof($opt);
    }

    #
    # Send string, sending HTTP headers if necessary.
    #

    private function addCdrFile($args)
    {
        list ($name, $opt, $meth, $crc, $zlen, $len, $ofs) = $args;

        # get attributes
        $comment = $opt['comment'] ? $opt['comment'] : '';

        # get dos timestamp
        $dts = $this->dostime($opt['time']);

        $fields = array( # (from V,F of APPNOTE.TXT)
            array('V', 0x02014b50), # central file header signature
            array('v', (6 << 8) + 3), # version made by
            array('v', (6 << 8) + 3), # version needed to extract
            array('v', 0x00), # general purpose bit flag
            array('v', $meth), # compresion method (deflate or store)
            array('V', $dts), # dos timestamp
            array('V', $crc), # crc32 of data
            array('V', $zlen), # compressed data length
            array('V', $len), # uncompressed data length
            array('v', strlen($name)), # filename length
            array('v', 0), # extra data len
            array('v', strlen($comment)), # file comment length
            array('v', 0), # disk number start
            array('v', 0), # internal file attributes
            array('V', 32), # external file attributes
            array('V', $ofs), # relative offset of local header
        );

        # pack fields, then append name and comment
        $ret = $this->packFields($fields) . $name . $comment;

        $this->send($ret);

        # increment cdr offset
        $this->cdr_ofs += strlen($ret);
    }

    #
    # Convert a UNIX timestamp to a DOS timestamp.
    #

    private function addCdrEof($opt = null)
    {
        $num = count($this->files);
        $cdr_len = $this->cdr_ofs;
        $cdr_ofs = $this->ofs;

        # grab comment (if specified)
        $comment = '';
        if ($opt && $opt['comment']) {
            $comment = $opt['comment'];
        }

        $fields = array( # (from V,F of APPNOTE.TXT)
            array('V', 0x06054b50), # end of central file header signature
            array('v', 0x00), # this disk number
            array('v', 0x00), # number of disk with cdr
            array('v', $num), # number of entries in the cdr on this disk
            array('v', $num), # number of entries in the cdr
            array('V', $cdr_len), # cdr size
            array('V', $cdr_ofs), # cdr ofs
            array('v', strlen($comment)), # zip file comment length
        );

        $ret = $this->packFields($fields) . $comment;
        $this->send($ret);
    }

    #
    # Create a format string and argument list for pack(), then call
    # pack() and return the result.
    #

    private function clear()
    {
        $this->files = array();
        $this->ofs = 0;
        $this->cdr_ofs = 0;
        $this->opt = array();
    }
}
