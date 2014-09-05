var gulp = require('gulp'),
    gutil = require('gulp-util'),
    watch = require('gulp-watch'),
    browserify = require('browserify'),
    source = require('vinyl-source-stream'),
    transform = require('vinyl-transform'),
    reactify = require('reactify');

gulp.task('build', function () {
    return browserify({
            debug: true,
            standalone: 'Application'
        })
        .transform(reactify)
        .add("./src/bootstrap.js")
        .bundle()
        .on('error', function(err){
            console.log(err.message);
        })
        .pipe(source('bundle.js'))
        .pipe(gulp.dest('./js'));
});

gulp.task('default', ['build'], function() {
    gulp.watch('src/**/*.js', ['build']);
});
