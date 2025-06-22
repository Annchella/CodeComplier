document.getElementById("runButton").addEventListener("click", function () {
    const sourceCode = document.getElementById("editor").value;
    const languageId = document.getElementById("language").value;

    fetch("compiler/Run.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            source_code: sourceCode,
            language_id: languageId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("API Response:", data); // helpful for debugging

        // ✅ Fix: Status is an object, get description
        document.getElementById("status").innerText = data.status?.description || "Unknown";

        // ✅ Fix: Output may be in stdout / stderr / compile_output
        if (data.stdout) {
            document.getElementById("output").innerText = data.stdout;
        } else if (data.stderr) {
            document.getElementById("output").innerText = data.stderr;
        } else if (data.compile_output) {
            document.getElementById("output").innerText = data.compile_output;
        } else {
            document.getElementById("output").innerText = "No output.";
        }

        document.getElementById("time").innerText = data.time + "s";
        document.getElementById("memory").innerText = data.memory + " KB";
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("output").innerText = "Something went wrong.";
    });
});