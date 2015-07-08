/*
 * This is NOT free software. You may learn from and experiment with this code but you may not redistribute it or use it in any commercial application without the explicit prior consent of the author.
 * Burak Kanber
 * burak@burakkanber.com
 * October 2012
 */

var canvas; 
var index;
var ctx;
var height = 400;
var width = 400;
var means = [];
var assignments = [];
var dataExtremes;
var dataRange;
var drawDelay = 200;
var index;
var dataindex = [];
var k_clusters =0;

function setup(k)
{
	k_clusters = k;
	canvas = document.getElementById('canvas');
	ctx = canvas.getContext('2d');
	dataExtremes = getDataExtremes(data);
	dataRange = getDataRanges(dataExtremes);
	means = initMeans(k);
	makeAssignments();
	draw();
	setTimeout(run, drawDelay);
}


function getDataRanges(extremes) 
{
	var ranges = [];
	for (var dimension in extremes)
	{
		ranges[dimension] = extremes[dimension].max - extremes[dimension].min;
	}
	return ranges;
}

function getDataExtremes(points)
{
	var extremes = [];
	for (var i in data)
	{
		var point = data[i];
		for (var dimension in point)
		{
			if(!extremes[dimension])
			{
				extremes[dimension] = {min: 1000, max: 0};
			}

			if (point[dimension] < extremes[dimension].min)
			{
				extremes[dimension].min = point[dimension];
			}

			if (point[dimension] > extremes[dimension].max)
			{
				extremes[dimension].max = point[dimension];
			}
		}
	}
	return extremes;
}


function initMeans(k)
{
	if (!k)
	{
		k = 3;
	}
	else
	{
		if(k>data.length)
		{
			k = data.length;
		}
	}

	for(var i=0; i<k; i++)
	{
		var mean = [];
		//  console.log(dataExtremes);

		// for (var dimension in dataExtremes)
		// {
		//     mean[dimension] = dataExtremes[dimension].min + ( Math.random() * dataRange[dimension] );

		// }
		// console.log(data);
		mean[0] = data[i][0];
		mean[1] = data[i][1];

		// console.log(mean);
		means.push(mean);
	}
    return means;
};

function makeAssignments()
{
	for (var i in data)
	{
		var point = data[i];
		var distances = [];
		for (var j in means)
		{
			var mean = means[j];
			var sum = 0;
			for (var dimension in point)
			{
				var difference = point[dimension] - mean[dimension];
				difference *= difference;
				sum += difference;
			}
			distances[j] = Math.sqrt(sum);
		}
		assignments[i] = distances.indexOf( Math.min.apply(null, distances) );
	}
	index = assignments;   
}


function moveMeans()
{

    makeAssignments();

    var sums = Array( means.length );
    var counts = Array( means.length );
    var moved = false;

    for (var j in means)
    {
        counts[j] = 0;
        sums[j] = Array( means[j].length );
        for (var dimension in means[j])
        {
            sums[j][dimension] = 0;
        }
    }

    for (var point_index in assignments)
    {
        var mean_index = assignments[point_index];
        var point = data[point_index];
        var mean = means[mean_index];

        counts[mean_index]++;

        for (var dimension in mean)
        {
            sums[mean_index][dimension] += point[dimension];
        }
    }

    for (var mean_index in sums)
    {
        //console.log(counts[mean_index]);
        if ( 0 === counts[mean_index] ) 
        {
            sums[mean_index] = means[mean_index];
          //  console.log("Mean with no points");
          //  console.log(sums[mean_index]);

            for (var dimension in dataExtremes)
            {
                sums[mean_index][dimension] = dataExtremes[dimension].min + ( Math.random() * dataRange[dimension] );
            }
            continue;
        }

        for (var dimension in sums[mean_index])
        {
            sums[mean_index][dimension] /= counts[mean_index];
        }
    }

    if (means.toString() !== sums.toString())
    {
        moved = true;
    }

    means = sums;

    return moved;

}

function run()
{
	var moved = moveMeans();
	draw();
	if(moved)
	{
		setTimeout(run, drawDelay);
	}
	else
	{
		assignindextomap(index);
	}
}

function draw()
{
	index = assignments;
	ctx.clearRect(0,0,width, height);

	ctx.globalAlpha = 0.3;
	for (var point_index in assignments)
	{
		var mean_index = assignments[point_index];
		var point = data[point_index];
		var mean = means[mean_index];

		ctx.save();

		ctx.strokeStyle = 'blue';
		ctx.beginPath();
		ctx.moveTo(
		(point[0] - dataExtremes[0].min + 1) * (width / (dataRange[0] + 2) ),
		(point[1] - dataExtremes[1].min + 1) * (height / (dataRange[1] + 2) )
		);
		ctx.lineTo(
		(mean[0] - dataExtremes[0].min + 1) * (width / (dataRange[0] + 2) ),
		(mean[1] - dataExtremes[1].min + 1) * (height / (dataRange[1] + 2) )
		);
		ctx.stroke();
		ctx.closePath();

		ctx.restore();
	}
	ctx.globalAlpha = 1;

	for (var i in data)
	{
		ctx.save();

		var point = data[i];

		var x = (point[0] - dataExtremes[0].min + 1) * (width / (dataRange[0] + 2) );
		var y = (point[1] - dataExtremes[1].min + 1) * (height / (dataRange[1] + 2) );

		ctx.strokeStyle = '#333333';
		ctx.translate(x, y);
		ctx.beginPath();
		ctx.arc(0, 0, 5, 0, Math.PI*2, true);
		ctx.stroke();
		ctx.closePath();

		ctx.restore();
	}

	for (var i in means)
	{
		ctx.save();
		var point = means[i];
		var x = (point[0] - dataExtremes[0].min + 1) * (width / (dataRange[0] + 2) );
		var y = (point[1] - dataExtremes[1].min + 1) * (height / (dataRange[1] + 2) );
		ctx.fillStyle = 'green';
		ctx.translate(x, y);
		ctx.beginPath();
		ctx.arc(0, 0, 5, 0, Math.PI*2, true);
		ctx.fill();
		ctx.closePath();
		ctx.restore();
	}

}

function getPath()
{
	var formData = {
	'data' : dataindex,
	'means':k_clusters
	}
	// console.log(dataindex);
	$.ajax({
	type     : 'POST',
	url    : 'getcluster.php',
	data     : formData,
	datatype : 'json',
	encode   : true,
	})
	.success(function(data){
	// console.log(data);
	$('.clusters').html(data);
	})
}


function assignindextomap(index){
  //console.log(index);

        function initialize(data,index) {
        var map_options = {
            center: new google.maps.LatLng(28.592140,77.046048),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var google_map = new google.maps.Map(document.getElementById("map-canvas"), map_options);

        var info_window = new google.maps.InfoWindow({
            content: 'loading'
        });
        //var dataindex1 = [];
        var t = [];
        var x = [];
        var y = [];
        var h = [];
        var ic = [];
        var varun = [];
        for(var i=0; i<data.length; i++)
        {
            varun = [index[i], data[i]];
            dataindex.push(varun);
            t.push('Location'+ index[i]);
            ic.push(index[i]);
            x.push(data[i][0]);
            y.push(data[i][1]);
            h.push('<p><strong>Location Name '+i+'</strong><br/>index '+index[i]+'</p>');
        }

        getPath();

        var i = 0;
        var iconBase = global_icon_base;
        for ( item in t ) {
            var m = new google.maps.Marker({
                map:       google_map,
                animation: google.maps.Animation.DROP,
                title:     t[i],
                position:  new google.maps.LatLng(x[i],y[i]),
                html:      h[i],
                icon: iconBase+ic[i]+'.png'
            });

            google.maps.event.addListener(m, 'click', function() {
                info_window.setContent(this.html);
                info_window.open(google_map, this);
            });
            i++;
        }
    }

    initialize(data,index);

  }