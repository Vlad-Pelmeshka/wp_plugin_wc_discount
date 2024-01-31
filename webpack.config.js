const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  mode: 'production',
  entry: {
    main: './assets/index.js',
    public: './assets/public.js', 
  },
  devServer: {
    contentBase: './dist',
  },
  module: {
    rules: [
      { 
        test: /\.js$/, 
        exclude: /node_modules/, 
        loader: "babel-loader" 
      },
      {
        test: /\.s[ac]ss$/,
        use: [
            {
              loader: MiniCssExtractPlugin.loader,
              options: {
                publicPath: (resourcePath, context) => {
                  return path.relative(path.dirname(resourcePath), context) + '/';
                },
              },
            },
            "css-loader",
            "sass-loader",
        ],
      },
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
        filename: "[name].css",
        chunkFilename: "[id].css",
    }),
  ],
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist'),
  },
};