const profile = document.getElementById("profile");

function goProfile()
{
    window.location = profile.value.toLowerCase();
}

profile.addEventListener("keypress", function(e) {
    if(e.key === "Enter")
    {
        goProfile();
    }
});