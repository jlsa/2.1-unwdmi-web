import gulp from 'gulp';
import { env as args } from 'gulp-util';
import { configure as babelify } from 'babelify';
import browserify from 'browserify';
import buffer from 'gulp-buffer';
import uglify from 'gulp-uglify';
import sass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';
import source from 'vinyl-source-stream';
import when from 'gulp-if';

gulp.task('sass', () => {
    return gulp.src('./resources/assets/sass/app.scss')
        .pipe(sourcemaps.init())
            .pipe(sass())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/style'));
});

gulp.task('js', () => {
    const bundler = browserify({
        debug: !args.minify,
        entries: './resources/assets/js/app.js'
    })
    .transform(babelify({ stage: 0 }));

    return bundler.bundle()
        .pipe(source('app.js'))
        .pipe(when(args.minify, buffer()))
        .pipe(when(args.minify, uglify()))
        .pipe(gulp.dest('public/js'));
});

gulp.task('default', [ 'sass' ]);
