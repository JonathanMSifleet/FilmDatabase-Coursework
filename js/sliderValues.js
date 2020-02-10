document.addEventListener("DOMContentLoaded", initialiseWebPage)

function initialiseWebPage() {

    // event listeners:

    const minRatingSlider = document.getElementById("minRatingSlider");
    minRatingSlider.addEventListener("input", updateMinRatingLabel);
    const minRatingLabel = document.getElementById("minRatingLabel");

    const minPopSlider = document.getElementById("minPopSlider");
    minPopSlider.addEventListener("input", updateMinPopLabel);
    const minPopLabel = document.getElementById("minPopLabel");

    const minYearSlider = document.getElementById("minYearSlider");
    minYearSlider.addEventListener("input", updateMinYearLabel);
    const minYearLabel = document.getElementById("minYearLabel");

    const maxYearSlider = document.getElementById("maxYearSlider");
    maxYearSlider.addEventListener("input", updateMaxYearLabel);
    const maxYearLabel = document.getElementById("maxYearLabel");

    const minRuntimeSlider = document.getElementById("minRuntimeSlider");
    minRuntimeSlider.addEventListener("input", updateMinRuntimeLabel);
    const minRuntimeLabel = document.getElementById("minRuntimeLabel");

    const maxRuntimeSlider = document.getElementById("maxRuntimeSlider");
    maxRuntimeSlider.addEventListener("input", updateMaxRuntimeLabel);
    const maxRuntimeLabel = document.getElementById("maxRuntimeLabel");

    const minVotesSlider = document.getElementById("minVotesSlider");
    minVotesSlider.addEventListener("input", updateMinVotesLabel);
    const minVotesLabel = document.getElementById("minVotesLabel");

    const minBudgetSlider = document.getElementById("minBudgetSlider");
    minBudgetSlider.addEventListener("input", updateMinBudgetLabel);
    const minBudgetLabel = document.getElementById("minBudgetLabel");

    const maxBudgetSlider = document.getElementById("maxBudgetSlider");
    maxBudgetSlider.addEventListener("input", updateMaxBudgetLabel);
    const maxBudgetLabel = document.getElementById("maxBudgetLabel");

    const minRevenueSlider = document.getElementById("minRevenueSlider");
    minRevenueSlider.addEventListener("input", updateMinRevenueLabel);
    const minRevenueLabel = document.getElementById("minRevenueLabel");

    const maxRevenueSlider = document.getElementById("maxRevenueSlider");
    maxRevenueSlider.addEventListener("input", updateMaxRevenueLabel);
    const maxRevenueLabel = document.getElementById("maxRevenueLabel");

    function updateMinRatingLabel() {
        minRatingLabel.innerHTML = minRatingSlider.value;
    }

    function updateMinPopLabel() {
        minPopLabel.innerHTML = minPopSlider.value;
    }

    function updateMinYearLabel() {
        minYearLabel.innerHTML = minYearSlider.value;
    }

    function updateMaxYearLabel() {
        maxYearLabel.innerHTML = maxYearSlider.value;
    }

    function updateMinRuntimeLabel() {
        minRuntimeLabel.innerHTML = minRuntimeSlider.value;
    }

    function updateMaxRuntimeLabel() {
        maxRuntimeLabel.innerHTML = maxRuntimeSlider.value;
    }

    function updateMinVotesLabel() {
        minVotesLabel.innerHTML = minVotesSlider.value;
    }

    function updateMinBudgetLabel() {
        minBudgetLabel.innerHTML = minBudgetSlider.value;
    }

    function updateMaxBudgetLabel() {
        maxBudgetLabel.innerHTML = maxBudgetSlider.value;
    }

    function updateMinRevenueLabel() {
        minRevenueLabel.innerHTML = minRevenueSlider.value;
    }

    function updateMaxRevenueLabel() {
        maxRevenueLabel.innerHTML = maxRevenueSlider.value;
    }

}