const themeLocation = "./frontend/web/";
const urlToPreview = "http://swift-delivery.infinitum.tech";
const paths = {
  php: {
    // watch: "./**/*.php",
  },
  styles: {
    src: themeLocation + "scss/style.scss",
    dist: themeLocation + "css",
    watch: themeLocation + "scss/**/*.scss",
  },
  scripts: {
    // src: [themeLocation + "js/modules/*.js", themeLocation + "js/scripts.js"],
    // dist: themeLocation + "js/",
    watch: [
      themeLocation + "js/modules/**/*.js",
      themeLocation + "js/scripts.js",
    ],
  },
  images: {
    src: [
      themeLocation + "img/**/*.{jpg,jpeg,png,gif}",
      // themeLocation + "!img/svg/*.svg",
      // themeLocation + "!img/favicon.{jpg,jpeg,png,gif}",
    ],
    dist: themeLocation + "img/",
    watch: themeLocation + "img/**/*.{jpg,jpeg,png,gif}",
  },
  sprites: {
    src: themeLocation + "img/svg/*.svg",
    dist: themeLocation + "img/sprites/",
    watch: themeLocation + "img/svg/*.svg",
  },
  favicons: {
    src: themeLocation + "img/favicon.{jpg,jpeg,png,gif}",
    dist: themeLocation + "img/favicons/",
  },
};

export default { paths, themeLocation, urlToPreview };
