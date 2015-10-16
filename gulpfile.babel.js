import gulp from 'gulp';
import { configure as babelify } from 'babelify';
import browserify from 'browserify';
import sass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';
import source from 'vinyl-source-stream';

gulp.task('sass', () => {
    return gulp.src('./resources/assets/sass/app.scss')
        .pipe(sourcemaps.init())
            .pipe(sass())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('public/style'));
});

gulp.task('js', () => {
    const bundler = browserify({
        debug: true,
        entries: './resources/assets/js/app.js'
    })
    .transform(babelify({ stage: 0 }));

    return bundler.bundle()
        .pipe(source('app.js'))
        .pipe(gulp.dest('public/js'));
});

gulp.task('default', [ 'sass' ]);
