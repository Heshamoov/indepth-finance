$(document).ready(function () {
        var select = document.getElementById('grade');
        var httpgrades = new XMLHttpRequest();
        httpgrades.onreadystatechange = function () {
            if (this.readyState === 4) {
                var str = this.responseText;
                document.getElementById("debug").innerHTML = this.responseText;
                gradesArray = str.split("\t");
            }
        };

        httpgrades.open("GET", "../mysql/grades.php", false);
        httpgrades.send();

        $('#grade').multiselect('destroy');

        delete gradesArray[gradesArray.length - 1];


        select.add(new Option("Grade"));
        for (var i in gradesArray)
            select.add(new Option(gradesArray[i]));
    }
)