<!DOCTYPE html>
<html>
<body>

<p>Click "Click it" to call a function with arguments</p>

<button onclick="getPlayerDetails('Rohit sharna','Player')">Click it</button>

<p id="example"></p>

<script>
function getPlayerDetails(name,job) {
  document.getElementById("example").innerHTML = "Grettings to " + name + ", the " + job + ".";
}
</script>

</body>
</html>