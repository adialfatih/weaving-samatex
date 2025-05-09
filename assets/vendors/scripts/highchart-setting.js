

// chart 5
Highcharts.chart('chart5', {
	title: {
		text: 'Pie point CSS'
	},
	xAxis: {
		categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	},
	series: [{
		type: 'pie',
		allowPointSelect: true,
		keys: ['name', 'y', 'selected', 'sliced'],
		data: [
		['Apples', 29.9, false],
		['Pears', 71.5, false],
		['Oranges', 106.4, false],
		['Plums', 129.2, false],
		['Bananas', 144.0, false],
		['Peaches', 176.0, false],
		['Prunes', 135.6, true, true],
		['Avocados', 148.5, false]
		],
		showInLegend: true
	}]
});

// chart 6
Highcharts.chart('chart6', {
	chart: {
		type: 'pie',
		options3d: {
			enabled: true,
			alpha: 45
		}
	},
	title: {
		text: 'Contents of Highsoft\'s weekly fruit delivery'
	},
	subtitle: {
		text: '3D donut in Highcharts'
	},
	plotOptions: {
		pie: {
			innerSize: 100,
			depth: 45
		}
	},
	series: [{
		name: 'Delivered amount',
		data: [
		['Bananas', 8],
		['Kiwi', 3],
		['Mixed nuts', 1],
		['Oranges', 6],
		['Apples', 8],
		['Pears', 4],
		['Clementines', 4],
		['Reddish (bag)', 1],
		['Grapes (bunch)', 1]
		]
	}]
});

// chart 7
Highcharts.chart('chart7', {
	chart: {
		type: 'gauge',
		plotBackgroundColor: null,
		plotBackgroundImage: null,
		plotBorderWidth: 0,
		plotShadow: false
	},
	title: {
		text: 'Speedometer'
	},
	pane: {
		startAngle: -150,
		endAngle: 150,
		background: [{
			backgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
				[0, '#FFF'],
				[1, '#333']
				]
			},
			borderWidth: 0,
			outerRadius: '109%'
		}, {
			backgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
				[0, '#333'],
				[1, '#FFF']
				]
			},
			borderWidth: 1,
			outerRadius: '107%'
		}, {
		}, {
			backgroundColor: '#DDD',
			borderWidth: 0,
			outerRadius: '105%',
			innerRadius: '103%'
		}]
	},
	yAxis: {
		min: 0,
		max: 200,

		minorTickInterval: 'auto',
		minorTickWidth: 1,
		minorTickLength: 10,
		minorTickPosition: 'inside',
		minorTickColor: '#666',

		tickPixelInterval: 30,
		tickWidth: 2,
		tickPosition: 'inside',
		tickLength: 10,
		tickColor: '#666',
		labels: {
			step: 2,
			rotation: 'auto'
		},
		title: {
			text: 'km/h'
		},
		plotBands: [{
			from: 0,
			to: 120,
			color: '#55BF3B'
		}, {
			from: 120,
			to: 160,
			color: '#DDDF0D'
		}, {
			from: 160,
			to: 200,
			color: '#DF5353'
		}]
	},
	series: [{
		name: 'Speed',
		data: [80],
		tooltip: {
			valueSuffix: ' km/h'
		}
	}]
},
function (chart) {
	if (!chart.renderer.forExport) {
		setInterval(function () {
			var point = chart.series[0].points[0],
			newVal,
			inc = Math.round((Math.random() - 0.5) * 20);

			newVal = point.y + inc;
			if (newVal < 0 || newVal > 200) {
				newVal = point.y - inc;
			}

			point.update(newVal);

		}, 3000);
	}
});

// chart 8
Highcharts.chart('chart8', {
	chart: {
		type: 'boxplot'
	},
	title: {
		text: 'Highcharts Box Plot Example'
	},
	legend: {
		enabled: false
	},
	xAxis: {
		categories: ['1', '2', '3', '4', '5'],
		title: {
			text: 'Experiment No.'
		}
	},
	yAxis: {
		title: {
			text: 'Observations'
		},
		plotLines: [{
			value: 932,
			color: 'red',
			width: 1,
			label: {
				text: 'Theoretical mean: 932',
				align: 'center',
				style: {
					color: 'gray'
				}
			}
		}]
	},
	series: [{
		name: 'Observations',
		data: [
		[760, 801, 848, 895, 965],
		[733, 853, 939, 980, 1080],
		[714, 762, 817, 870, 918],
		[724, 802, 806, 871, 950],
		[834, 836, 864, 882, 910]
		],
		tooltip: {
			headerFormat: '<em>Experiment No {point.key}</em><br/>'
		}
	}, {
		name: 'Outlier',
		color: Highcharts.getOptions().colors[0],
		type: 'scatter',
		data: [
		[0, 644],
		[4, 718],
		[4, 951],
		[4, 969]
		],
		marker: {
			fillColor: 'white',
			lineWidth: 1,
			lineColor: Highcharts.getOptions().colors[0]
		},
		tooltip: {
			pointFormat: 'Observation: {point.y}'
		}
	}]

});