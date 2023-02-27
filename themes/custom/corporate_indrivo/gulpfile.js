/**
 * @file
 */

"use strict";

// Declare the node_modules variables.
const gulp = require("gulp"),
  sourcemaps = require("gulp-sourcemaps"),
  plumber = require("gulp-plumber"),
  sass = require("gulp-sass")(require("sass")),
  autoprefixer = require("gulp-autoprefixer"),
  cleanCSS = require("gulp-clean-css"),
  rename = require("gulp-rename"),
  babel = require("gulp-babel"), // @babel/core @babel/preset-env
  minify = require("gulp-minify");

// Compile *.scss to *.css files.
gulp.task("sass", () => {
  return gulp
    .src(["./scss/*.scss", "./scss/**/*.scss"])
    .pipe(sourcemaps.init())
    .pipe(plumber())
    .pipe(sass())
    .pipe(
      sass({
        errLogToConsole: true,
      })
    )
    .on("error", catchErr)
    .pipe(autoprefixer())
    .pipe(gulp.dest("./css"))
    .pipe(cleanCSS())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest("./css"));
});

// Rewrite to ES2015 and Minify *.js.
gulp.task("compile-js", () => {
  return gulp
    .src(["./js/*.js", "!./js/*.min.js"])
    .pipe(
      babel({
        presets: ["@babel/env"],
      })
    )
    .pipe(
      minify({
        ext: {
          min: ".min.js",
        },
        ignoreFiles: [".min.js"],
        noSource: true,
      })
    )
    .pipe(gulp.dest("./js"));
});

// Watcher for  edit files [./scss/*.scss, ./js/*.js] and call compile functions.
gulp.task("watch", () => {
  gulp.watch(["./scss/*.scss", "./scss/**/*.scss"], gulp.series("sass"));
  gulp.watch(["./js/*.js", "!./js/*.min.js"], gulp.series("compile-js"));
});

// Default task for gulp.
gulp.task("default", gulp.series(["sass", "compile-js", "watch"]));

// Error catching.
function catchErr(e) {
  console.log(e);
  this.emit("end");
}
