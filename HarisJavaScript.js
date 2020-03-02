src = "https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js" >

type = "text/javascript" >
    $(document).ready(function () {
        $(".register").click(function () {
            $(".other").show();
            $(".content").hide();
            $(".register").addClass('active');
            $(".login").removeClass('active');
        });
        $(".login").click(function () {
            $(".content").show();
            $(".other").hide();
            $(".login").addClass('active');
            $(".register").removeClass('active');
        });
    });

/*const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', function(){
    
    container.classList.add('right-panel-active');
});

signInButton.addEventListener('click', function(){
    
    container.classList.remove('right-panel-active');
});

/*
signUpButton.addEventListener('click', () => {
    container.classList.add('right-panel-active');
});
*/

/*
signInButton.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
});
*/