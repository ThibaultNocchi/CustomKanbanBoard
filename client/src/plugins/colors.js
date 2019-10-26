let colors = {
    red: '#FFCDD2',
    pink: '#F8BBD0',
    purple: '#E1BEE7',
    blue: '#BBDEFB',
    green: '#C8E6C9',
    yellow: '#FFF9C4',
    orange: '#FFE0B2',
    brown: '#D7CCC8',
    grey: '#CFD8DC'
}

let colors_inverted = Object.keys(colors).reduce((ret, key) => {
    ret[colors[key]] = key;
    return ret;
  }, {})

module.exports = {colors, colors_inverted}