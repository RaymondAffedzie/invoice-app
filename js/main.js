$(document).ready(function() {
	// logout
	$('#logout').click(function(e) {
		e.preventDefault();

		var url = "../logic/logout.php";
		var data = {
			data: "logout"
		};

		$.ajax({
			type: 'POST',
			url: url,
			data: JSON.stringify(data),
			contentType: 'application/json',
			processData: false,
			dataType: 'json',
			success: function(response) {
				console.log("response data: ", response);
				if (response.status === true) {
					swal("Success", response.message, "success").then(function() {
						if (response.redirect) {
							window.location.href = response.redirect;
						}
					});
				} else {
					swal("Caution", response.message || "Logout failed", "warning").then(function() {
						if (response.redirect) {
							window.location.href = response.redirect;
						}
					});
				}
			},
			error: function(xhr, status, error) {
				swal("Error", status + ": " + error, "error");
			}
		});
	});

});

function postReq(url, data) {
	$.ajax({
		url: url,
		type: "POST",
		data: data,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (response) {
			if (response.status == true) {
				swal("Success", response.message, "success").then(function () {
					if (response.redirect) {
						window.location.href = response.redirect;
					}
				});
			} else if (response.status == false) {
				swal("Caution", response.message, "warning").then(function () {
					if (response.redirect) {
						window.location.href = response.redirect;
					}
				});
			} else {
				swal("Danger", response.message, "error").then(function () {
					if (response.redirect) {
						window.location.href = response.redirect;
					}
				});
			}
		},
		error: function (xhr, status, error) {
			swal(status, error, "error");
		},
	});
}

function daySuffix(day) {
	if (day >= 11 && day <= 13) {
		return "th";
	}
	switch (day % 10) {
		case 1:
			return "st";
		case 2:
			return "nd";
		case 3:
			return "rd";
		default:
			return "th";
	}
}

function formatDate(dateTime) {
	const options = {
		hour: "2-digit",
		minute: "2-digit",
	};
	const date = new Date(dateTime);
	const day = date.getDate();
	const month = date.toLocaleDateString("en-GB", {
		month: "long",
	});
	const year = date.getFullYear();
	const time = date.toLocaleTimeString("en-US", options);

	const formattedDate = `${day}${daySuffix(day)} ${month}, ${year}. ${time}`;
	return formattedDate;
}