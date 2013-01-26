$(function() {
				
	/*
	the json config obj.
	name: the class given to the element where you want the tooltip to appear
	bgcolor: the background color of the tooltip
	color: the color of the tooltip text
	text: the text inside the tooltip
	time: if automatic tour, then this is the time in ms for this step
	position: the position of the tip. Possible values are
		TL	top left
		TR  top right
		BL  bottom left
		BR  bottom right
		LT  left top
		LB  left bottom
		RT  right top
		RB  right bottom
		T   top
		R   right
		B   bottom
		L   left
	 */
	var config = [
		{
			"name" 		: "karakter_sayaci_tutucu",
			"bgcolor"	: "black",
			"color"		: "white",
			"position"	: "TL",
			"text"		: "You can create a tour to explain the functioning of your app",
			"time" 		: 5000
		},
		{
			"name" 		: "tour_2",
			"bgcolor"	: "black",
			"color"		: "white",
			"text"		: "Give a class to the points of your walkthrough",
			"position"	: "BL",
			"time" 		: 5000
		},
		{
			"name" 		: "tour_3",
			"bgcolor"	: "black",
			"color"		: "white",
			"text"		: "Customize the navigation buttons",
			"position"	: "BL",
			"time" 		: 5000
		},
		{
			"name" 		: "tour_4",
			"bgcolor"	: "black",
			"color"		: "white",
			"text"		: "You can also use the autoplay function where the user can just sit back and watch the whole tour",
			"position"	: "TL",
			"time" 		: 5000
		},
		{
			"name" 		: "tour_5",
			"bgcolor"	: "black",
			"color"		: "white",
			"text"		: "You can indicate the direction of the tooltip arrow for each tour point",
			"position"	: "TL",
			"time" 		: 5000
		},
		{
			"name" 		: "tour_6",
			"bgcolor"	: "#111199",
			"color"		: "white",
			"text"		: "Mark important tour points in a different color",
			"position"	: "BL",
			"time" 		: 5000
		},
		{
			"name" 		: "tour_7",
			"bgcolor"	: "black",
			"color"		: "white",
			"text"		: "Automatically scrolls to the right place of the website",
			"position"	: "TL",
			"time" 		: 5000
		}

	],
	//define if steps should change automatically
	autoplay	= true,
	//timeout for the step
	showtime,
	//current step of the tour
	step		= 0,
	//total number of steps
	total_steps	= config.length;
		
	//show the tour controls
});
