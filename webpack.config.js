const path = require("path");
const {GenerateSW} = require('workbox-webpack-plugin');
import settings from "./settings";

module.exports = {
  entry: {
    App: settings.themeLocation + "js/scripts.js",
  },
  output: {
    path: path.resolve(__dirname, settings.themeLocation + "js"),
    filename: "scripts-bundled.js",
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },
  plugins: [
    // Other webpack plugins...
    // new GenerateSW({
    // })
  ],
  mode: "development",
  // mode: "production",
};
