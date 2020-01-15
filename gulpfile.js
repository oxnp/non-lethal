'use strict';

var gulp = require('gulp'),watch = require('gulp-watch');
var sass = require('gulp-sass');
const gutil = require('gutil');
const ftp = require('vinyl-ftp');
var localFiles = ['./public/css/*','*.ico','*.js','*.html','*.css'];
var user = 'lethal@lethal.izzi.studio';
var password = 'wdsedoi?FzQu';
function getFtpConnection(){
    return ftp.create({
        host: '142.93.111.152',
        port: 21,
        user: user,
        password: password,
        log: gutil.log
    });
}
const remoteLocation = '/';


sass.compiler = require('node-sass');

gulp.task('sass', function () {
    return gulp.src('resources/sass/front.sass')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulp.dest('public/css'));
});

gulp.task('sass:watch', function () {
    gulp.watch('resources/sass/front.sass', gulp.series('sass'));
});

gulp.task('watch', function(){

    var conn = getFtpConnection()

    gulp.watch(localFiles).on('change', function(event) {
        return gulp.src(localFiles, {base: '.', buffer: false})
            .pipe(conn.newer(remoteLocation))
            .pipe(conn.dest(remoteLocation));
    })
    gulp.watch('resources/sass/front.sass', gulp.series('sass'));
})
