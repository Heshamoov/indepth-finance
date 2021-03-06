function search() {
    let date = new Date(document.querySelector("#start_date").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let start_date = year + '-' + month + '-' + day;

    date = new Date(document.querySelector("#end_date").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let end_date = year + '-' + month + '-' + day;

    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            archived_students_datatable();
        }
    };
    payments.open("GET", "mysql/students_tc.php?start_date=" + start_date + "&end_date=" + end_date, false);
    payments.send();
}

$(document).ready(function () {
    $('.form-check-input').click(function (e) {
        id = this.id;
        status = $(this).prop("checked");

        let update_tc = new XMLHttpRequest();
        update_tc.onreadystatechange = function () {
            if (this.readyState === 4) {
                document.getElementById('l' + id).innerHTML = this.responseText;
            }
        };
        update_tc.open("GET", "mysql/update_student_tc.php?id=" + this.id + "&status=" + status, false);
        update_tc.send();
    });
});