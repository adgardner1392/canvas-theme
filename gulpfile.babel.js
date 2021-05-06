import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import sassGlob from 'gulp-sass-glob';
import cleanCss from 'gulp-clean-css';
import concat from 'gulp-concat';
import gulpif from 'gulp-if';
import imagemin from 'gulp-imagemin';
import del from 'del';
import webpack from 'webpack-stream';
import browserSync from 'browser-sync';
import zip from 'gulp-zip';
import info from './package.json';
import svgSprite from 'gulp-svg-sprite';
import fs from 'fs';

const PRODUCTION = yargs.argv.prod;

export const styles = () => {
  return src( ['assets/src/scss/main.scss'] )
    .pipe( gulpif( ! PRODUCTION, sourcemaps.init( ) ) )
    .pipe( sassGlob() )
    .pipe( sass().on( 'error', sass.logError ) )
    .pipe( gulpif( PRODUCTION, postcss( [ autoprefixer ])))
    .pipe( gulpif( PRODUCTION, cleanCss( { compatibility:'ie8' } ) ) )
    .pipe( gulpif( ! PRODUCTION, sourcemaps.write() ) )
    .pipe( concat( 'theme.min.css' ) )
    .pipe( dest( 'assets/dist/css' ) )
    .pipe( server.stream() );
}

export const adminStyles = () => {
  return src( ['assets/src/scss/gutenberg.scss'] )
    .pipe( sassGlob() )
    .pipe( sass().on( 'error', sass.logError ) )
    .pipe( postcss( [ autoprefixer ] ) )
    .pipe( cleanCss( { compatibility:'ie8' } ) )
    .pipe( concat( 'gutenberg.min.css' ) )
    .pipe( dest( 'assets/dist/css' ) )
    .pipe( server.stream() );
}

export const images = () => {
  return src( 'assets/src/images/*.{jpg,jpeg,png,svg,gif}' )
    .pipe( gulpif( PRODUCTION, imagemin() ) )
    .pipe( dest( 'assets/dist/images' ) );
}

export const fonts = () => {
  return src( 'assets/src/fonts/*.{eot,woff,ttf,svg}' )
    .pipe( dest( 'assets/dist/fonts' ) );
}

export const scripts = () => {
    return src([
        'assets/src/js/*.js',
        'templates/blocks/**/*.js'
    ])
    .pipe( webpack({
        module: {
            rules: [{
                test: /\.js$/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: []
                    }
                }
            }]
        },
        mode: PRODUCTION ? 'production' : 'development',
        devtool: !PRODUCTION ? 'inline-source-map' : false,
        output: {
            filename: 'theme.min.js'
        },
    }))
    .pipe( dest( 'assets/dist/js' ) );
}

export const vectors = () => {

    return src( 'assets/src/vectors/*.svg' )
    .pipe( gulpif( PRODUCTION, imagemin() ) )
    .pipe( svgSprite({
        dest: '',
        shape: {
            dest: 'vectors' // Keep the intermediate files
        },
        svg: {
            xmlDeclaration: false
        },
        mode: {
            symbol: { // Activate the «view» mode
                dest: '',
                prefix: '',
                bust: false,
                sprite: "spritesheet.svg", // Sprite path and name
                render: {
                    scss: false // Activate Sass output (with default options)
                }
            }
        }
    }))
    .pipe( dest( 'assets/dist/' ) );
}

const server = browserSync.create();
export const serve = done => {
    server.init({
        proxy: info.proxy
    });
    done();
};

export const reload = done => {
    server.reload();
    done();
};

export const watchForChanges = () => {
    watch( [ 'templates/blocks/**/*.scss', 'assets/src/scss/**/*.scss', 'assets/src/scss/*.scss' ], series( packageStyles, adminStyles, styles, cacheBuster ) );
    watch( 'assets/src/images/*.{jpg,jpeg,png,svg,gif}', series( images, cacheBuster ) );
    watch( 'assets/src/fonts/*.{eot,woff,ttf,svg}', series( fonts, cacheBuster ) );
    watch( ['assets/src/js/*.js', 'templates/blocks/**/*.js' ], series( packageScripts, scripts, cacheBuster ) );
    watch( 'assets/src/vectors/*.svg', series( vectors, cacheBuster ) );
    watch( [ 'header.php', 'footer.php', 'templates/**/*.php', 'templates/**/**/*.php' , 'woocommerce/*.php' , 'woocommerce/**/*.php'  ], reload );
}

export const cacheBuster = done => {
    fs.writeFileSync( 'assets/dist/cache-buster', Date.now().toString(), (error) => {} );
    return done();
}

export const clean = () => del([ 'assets/dist' ]);

export const compress = () => {
    return src([
        '**/*',
        '**/**/',
        '!node_modules{,/**}',
        '!bundled{,/**}',
        '!assets/src{,/**}',
        '!.babelrc',
        '!.gitignore',
        '!gulpfile.babel.js',
        '!package.json',
        '!package-lock.json',
    ])
    .pipe( zip( `${info.name}.zip` ) )
    .pipe( dest( 'bundled' ) );
};

export const packageStyles = () => {
    return src([
        'node_modules/aos/dist/aos.css'
    ])
    .pipe( gulpif( ! PRODUCTION, sourcemaps.init( ) ) )
    .pipe( gulpif( ! PRODUCTION, sourcemaps.write() ) )
    .pipe( concat( 'components.min.css' ) )
    .pipe( dest( 'assets/dist/css' ) )
    .pipe( server.stream() );
}

export const packageScripts = () => {

    return src([
        'node_modules/aos/dist/aos.js'
    ])
    .pipe( concat( 'components.min.js' ) )
    .pipe( dest( 'assets/dist/js' ) )
    .pipe( server.stream() );
}

export const dev = series( clean, parallel( styles, packageStyles, adminStyles, images, packageScripts, scripts, fonts, vectors ), serve, watchForChanges );
export const build = series( clean, parallel( styles, packageStyles, adminStyles, images, packageScripts, scripts, fonts, vectors ), cacheBuster, compress );
export default dev;
