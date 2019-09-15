"use strict";

import settings from "./settings";

import {src, dest, watch, parallel, series} from "gulp";
import gulpif from "gulp-if";
import browsersync from "browser-sync";
import autoprefixer from "gulp-autoprefixer";
import babel from "gulp-babel";
import browserify from "browserify";
import watchify from "watchify";
import source from "vinyl-source-stream";
import buffer from "vinyl-buffer";
import uglify from "gulp-uglify";
import sass from "gulp-sass";
import groupmediaqueries from "gulp-group-css-media-queries";
import mincss from "gulp-clean-css";
import sourcemaps from "gulp-sourcemaps";
import rename from "gulp-rename";
import imagemin from "gulp-imagemin";
// import imageminPngquant from "imagemin-pngquant";
import imageminZopfli from "imagemin-zopfli";
import imageminMozjpeg from "imagemin-mozjpeg";
import imageminGiflossy from "imagemin-giflossy";
import favicons from "gulp-favicons";
import svgSprite from "gulp-svg-sprite";
import replace from "gulp-replace";
import rigger from "gulp-rigger";
import plumber from "gulp-plumber";
import debug from "gulp-debug";
import clean from "gulp-clean";
import webpack from "webpack";
import yargs from "yargs";

const argv = yargs.argv;
const production = !!argv.production;

// const smartgrid = require("smart-grid");
const tildeImporter = require("node-sass-tilde-importer");

export const server = done => {
    browsersync.init({
        notify: false,
        proxy: settings.urlToPreview,
        ghostMode: false,
    });
    done();
};

export const watchCode = done => {
    // watch(settings.paths.php.watch, function(done) {
    //   browsersync.reload();
    //   done();
    // });
    watch(settings.paths.styles.watch, styles);
    watch(settings.paths.scripts.watch, scripts);
    // watch(settings.paths.images.watch, images);
    watch(settings.paths.sprites.watch, sprites);
    done();
};

// export const cleanFiles = () =>
//   src("./dist/**/", { read: false })
//     .pipe(clean())
//     .pipe(
//       debug({
//         title: "Cleaning...",
//       })
//     );

// export const smartGrid = cb => {
//   smartgrid(settings.themeLocation + "sass/utils", {
//     outputStyle: "scss",
//     filename: "_smart-grid",
//     columns: 12, // number of grid columns
//     offset: "30px", // gutter width
//     mobileFirst: true,
//     container: {
//       fields: "15px",
//     },
//     breakPoints: {
//       xs: {
//         width: "320px",
//       },
//       sm: {
//         width: "576px",
//       },
//       md: {
//         width: "768px",
//       },
//       lg: {
//         width: "992px",
//       },
//       xl: {
//         width: "1200px",
//       },
//     },
//   });
//   cb();
// };

export const styles = () =>
    src(settings.paths.styles.src)
        .pipe(gulpif(!production, sourcemaps.init()))
        .pipe(plumber())
        .pipe(
            sass({
                importer: tildeImporter,
            })
        )
        .pipe(groupmediaqueries())
        .pipe(
            gulpif(
                production,
                autoprefixer({
                    browsers: ["last 12 versions", "> 1%", "ie 8", "ie 7"],
                })
            )
        )
        .pipe(
            gulpif(
                production,
                mincss({
                    compatibility: "ie8",
                    level: {
                        1: {
                            specialComments: 0,
                            removeEmpty: true,
                            removeWhitespace: true,
                        },
                        2: {
                            mergeMedia: true,
                            removeEmpty: true,
                            removeDuplicateFontRules: true,
                            removeDuplicateMediaBlocks: true,
                            removeDuplicateRules: true,
                            removeUnusedAtRules: false,
                        },
                    },
                })
            )
        )
        .pipe(
            gulpif(
                production,
                rename({
                    suffix: ".min",
                })
            )
        )
        .pipe(plumber.stop())
        .pipe(gulpif(!production, sourcemaps.write()))
        .pipe(dest(settings.paths.styles.dist))
        .pipe(
            debug({
                title: "CSS files",
            })
        )
        .pipe(browsersync.stream());

export const scripts = callback => {
    let config = require("./webpack.config.js");
    if (production) {
        config.mode = "production";
    }
    webpack(config, function (err, stats) {
        if (err) {
            console.log(err.toString());
        }

        console.log(stats.toString());
        callback();
    });
};

export const images = () =>
    src(settings.paths.images.src)
        .pipe(
            gulpif(
                production,
                imagemin([
                    imageminGiflossy({
                        optimizationLevel: 3,
                        optimize: 3,
                        lossy: 2,
                    }),
                    // imageminPngquant({
                    // 	speed: 5,
                    // 	quality: 75
                    // }),
                    imageminZopfli({
                        more: true,
                    }),
                    imageminMozjpeg({
                        progressive: true,
                        quality: 70,
                    }),
                    imagemin.svgo({
                        plugins: [
                            {removeViewBox: false},
                            {removeUnusedNS: false},
                            {removeUselessStrokeAndFill: false},
                            {cleanupIDs: false},
                            {removeComments: true},
                            {removeEmptyAttrs: true},
                            {removeEmptyText: true},
                            {collapseGroups: true},
                        ],
                    }),
                ])
            )
        )
        .pipe(dest(settings.paths.images.dist))
        .pipe(
            debug({
                title: "Images",
            })
        )
        .on("end", browsersync.reload);

export const sprites = () =>
    src(settings.paths.sprites.src)
        .pipe(
            svgSprite({
                mode: {
                    stack: {
                        sprite: "../sprite.svg",
                    },
                },
            })
        )
        .pipe(dest(settings.paths.sprites.dist))
        .pipe(
            debug({
                title: "Sprites",
            })
        )
        .on("end", browsersync.reload);

export const favs = () =>
    src(settings.paths.favicons.src)
        .pipe(
            favicons({
                icons: {
                    appleIcon: true,
                    favicons: true,
                    online: false,
                    appleStartup: false,
                    android: false,
                    firefox: false,
                    yandex: false,
                    windows: false,
                    coast: false,
                },
            })
        )
        .pipe(dest(settings.paths.favicons.dist))
        .pipe(
            debug({
                title: "Favicons",
            })
        );

export const development = series(
    // cleanFiles,
    // smartGrid,
    parallel(scripts, styles, images, sprites, favs),
    parallel(watchCode, server)
);

export const prod = series(
    // cleanFiles,
    // smartGrid,
    scripts,
    styles,
    images,
    sprites,
    favs
);

export default development;
