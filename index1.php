<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate and Copy Color</title>
    <style>
        #colorBox {
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div>
        <div id="colorBox"></div>
        <button onclick="generateAndCopyColor()">Generate and Copy Color</button>
    </div>

    <script>
        function generateRandomColor() {
            // Generate a random hex color
            return '#' + Math.floor(Math.random()*16777215).toString(16);
        }

        function generateAndCopyColor() {
            // Generate a random color
            var randomColor = generateRandomColor();

            // Display the color
            document.getElementById('colorBox').style.backgroundColor = randomColor;

            // Copy the color to the clipboard
            var tempInput = document.createElement('input');
            tempInput.value = randomColor;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            // Alert the user
            alert('Color copied to clipboard: ' + randomColor);
        }
    </script>
</body>
</html>
