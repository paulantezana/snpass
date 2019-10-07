import {src, dest, task, watch, series, parallel} from 'gulp';

// CSS related plugins
import sass from 'gulp-sass';
// import autoprefixer from 'gulp-autoprefixer';

// JavaScript plugins
// import babel from 'gulp-babel';
import minify from 'gulp-minify';

// Utilities
import sourcemaps from 'gulp-sourcemaps';

function scss(done) {
    src(['./src/scss/*.scss'])
        .pipe(sourcemaps.init())
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'compressed'
        }))
        .on('error', console.error.bind(console))
        // .pipe(autoprefixer({browsers: ['last 2 versions', '> 5%', 'Firefox ESR']}))
        // .pipe( rename( { suffix: '.min' } ) )
        // .pipe(sourcemaps.write('./'))
        .pipe(dest('./dist/css'))
    done();
};

function scrips(done) {
    src(['./src/script/*.js'])
        .pipe(sourcemaps.init())
        // .pipe(babel())
        .pipe(minify())
        // .pipe(sourcemaps.write('./'))
        .pipe(dest('./dist/script'))
    done();
};

// Files
function watch_files() {
    watch('./src/**/*.scss', series(scss));
    watch('./src/**/*.js', series(scrips));
}

// Default task
task("default", parallel(watch_files));