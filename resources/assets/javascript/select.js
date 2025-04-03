$(document).ready(function () {
    $('#courseSelect').on('change', function () {
        var selectedCourseID = $(this).val();
        console.log(selectedCourseID)
        $.ajax({
            url: 'takeattendance.php', 
            type: 'post',
            data: { courseID: selectedCourseID },
            success: function (response) {
                console.log(response);
                $('#studentTableContainer').html(response);
            }
        });
    });
});
