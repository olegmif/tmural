const webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");

const productionRules = [
  {
    test: /\.(js)$/,
    exclude: /node_modules/,
    use: ["babel-loader", "eslint-loader"],
  },
  {
    test: /\.(sa|sc|c)ss$/,
    use: [
      { loader: MiniCssExtractPlugin.loader },
      { loader: "css-loader", options: { sourceMap: false } },
      {
        loader: "postcss-loader",
        options: {
          sourceMap: false,
        },
      },
      { loader: "resolve-url-loader" },
      {
        loader: "sass-loader",
        options: { sourceMap: false },
      },
    ],
  },
  {
    test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico|mp4|webm)$/,
    use: [
      {
        loader: "file-loader",
        options: {
          name: "[path][name].[ext]",
        },
      },
    ],
  },
];

const developmentRules = [
  {
    test: /\.(js)$/,
    exclude: /node_modules/,
    use: ["babel-loader", "eslint-loader"],
  },
  {
    test: /\.(sa|sc|c)ss$/,
    use: [
      { loader: MiniCssExtractPlugin.loader },
      { loader: "css-loader", options: { sourceMap: true } },
      { loader: "resolve-url-loader" },
      {
        loader: "sass-loader",
        options: { sourceMap: true },
      },
    ],
  },
  {
    test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico|mp4|webm)$/,
    use: [
      {
        loader: "file-loader",
        options: {
          name: "[path][name].[ext]",
        },
      },
    ],
  },
];

module.exports = (env) => {
  return {
    mode: env.dev ? "development" : "production",
    entry: {
      index: "./src/js/index.js",
    },
    output: {
      path: __dirname + "/build",
      filename: "[name].js",
      clean: true,
    },
    devtool: env.dev ? "source-map" : false,
    module: {
      rules: env.dev || env.watch ? developmentRules : productionRules,
    },
    watch: env.watch,
    plugins: [
      new webpack.ProgressPlugin(),
      new MiniCssExtractPlugin({
        filename: "[name].css",
      }),
      // new CleanWebpackPlugin({ root: __dirname + "/build" }),
    ],
    optimization: {
      minimize: !env.dev,
      minimizer: [
        new TerserPlugin({
          test: /\.js(\?.*)?$/i,
        }),
      ],
    },
  };
};
