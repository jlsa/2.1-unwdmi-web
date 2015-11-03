import gulp from 'gulp';
import { env as args } from 'gulp-util';
import { configure as babelify } from 'babelify';
import browserify from 'browserify';
import buffer from 'gulp-buffer';
import uglify from 'gulp-uglify';
import cssnano from 'gulp-cssnano';
import sass from 'gulp-sass';
import postcss from 'gulp-postcss';
import postcssImport from 'postcss-import';
import sourcemaps from 'gulp-sourcemaps';
import source from 'vinyl-source-stream';
import when from 'gulp-if';

gulp.task('sass', () => {
    return gulp.src('./resources/assets/sass/app.scss')
        .pipe(sourcemaps.init())
            .pipe(sass({ includePaths: [ './node_modules' ] }))
            .pipe(postcss([ postcssImport() ]))
            .pipe(when(args.minify, cssnano()))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/style'));
});

gulp.task('js', () => {
    const bundler = browserify({
        debug: !args.minify,
        entries: './resources/assets/js/app.js'
    })
    .transform(babelify({ stage: 0, jsxPragma: 'element' }));

    return bundler.bundle()
        .pipe(source('app.js'))
        .pipe(when(args.minify, buffer()))
        .pipe(when(args.minify, uglify()))
        .pipe(gulp.dest('public/js'));
});

gulp.task('watch', () => {
  gulp.watch('./resources/assets/js/**/*.js', [ 'js' ])
  gulp.watch('./resources/assets/sass/**/*.scss', [ 'sass' ])
})

gulp.task('default', [ 'sass', 'js' ]);
