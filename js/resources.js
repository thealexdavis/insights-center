if ($('#resources_list')[0]) {
	var $listgrid = $('#resources_list').isotope({
		itemSelector: '.resource.active',
		percentPosition: true,
		layoutMode: 'fitRows'
	});
	equalHeights();
}

$(window).resize(function () {
	equalHeights();
});

function equalHeights() {
	maxHeight = 0;
	$('#resources_list a.resource').css('height', 'initial');
	if (window.innerWidth >= 768) {
		$('#resources_list a.resource').each(function (index) {
			if ($(this).outerHeight() > maxHeight) {
				maxHeight = $(this).outerHeight();
			}
		});
		$('#resources_list a.resource').css('height', maxHeight);
	}
}

$('#resource_search').click(function () {
	$('form#resource_search').show();
	setTimeout(() => {
		$('form#resource_search').addClass('active');
	}, 5);
});

// $( "p.loadmore a" ).click(function(e) {
// 	e.preventDefault();
// 	resourcesCount = $("#resources_list .resource").length;
// 	activeCount = $("#resources_list .resource.active").length;
// 	if((resourcesCount-activeCount) < 8){
// 		totalReveal = (resourcesCount-activeCount);
// 		$("p.loadmore").hide();
// 	} else {
// 		totalReveal = 8;
// 		$("p.loadmore").show();
// 	}
// 	for(x=(totalReveal - 1);x>=0;x--){
// 		$("#resources_list .resource:not(.active)").eq(x).addClass("active");
// 	}
// 	$('#resources_list').isotope( 'reloadItems' ).isotope();
// });

$('#resource_search a.close').click(function (e) {
	e.preventDefault();
	$('form#resource_search').removeClass('active');
	setTimeout(() => {
		$('form#resource_search').hide();
	}, 250);
});
