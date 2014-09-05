var gulp = require('gulp'),
    react = require('gulp-react'),
    gutil = require('gulp-util'),
    browserify = require('browserify'),
    source = require('vinyl-source-stream'),
    transform = require('vinyl-transform'),
    reactify = require('reactify');

gulp.task('react', function () {
    return gulp.src('./js/app.jsx')
        .pipe(react())
        .pipe(gulp.dest('./js'));
});

gulp.task('browserify', function () {
    return browserify({
            debug: true,
            standalone: 'Application'
        })
        .transform(reactify)
        .add("./js/bundle.jsx")
        .bundle()
        .on('error', function(err){
            console.log(err.message);
        })
        .pipe(source('bundle.js'))
        .pipe(gulp.dest('./js'));
});