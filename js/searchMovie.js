document.addEventListener("DOMContentLoaded", initialiseWebPage);

function initialiseWebPage() {

    const searchType = document.getElementById("searchType");
    const orderType = document.getElementById("orderType");

    searchType.addEventListener("change", changeResultsType);

    function changeResultsType() {
        var type = searchType.options[searchType.selectedIndex].value;

        console.log(type);

        for (var i = orderType.length; i >= 0; i--) {
            orderType.remove(i);
        }

        if (type != name) {
            var opt = new Option("Name", "credit_name");
            opt.selected = true;
            orderType.appendChild(opt);
        }

    }
}