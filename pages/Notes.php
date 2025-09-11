<?php
include('../includes/db.php');
$languages = ['Java','Python','C++','JavaScript'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Notes</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-100">

<header class="bg-green-600 text-white p-4">
<h1 class="text-2xl font-bold">Programming Notes</h1>
</header>

<main class="p-6">

<!-- Search -->
<div class="mb-6">
<input id="searchInput" type="text" placeholder="Search notes..." class="w-full p-2 border rounded-lg">
</div>

<!-- Tabs -->
<div class="flex space-x-4 mb-6">
<?php foreach($languages as $i=>$lang){ ?>
<button class="tabBtn px-4 py-2 rounded-lg <?= $i==0?'bg-green-600 text-white':'bg-gray-200' ?>" data-tab="<?= $lang ?>"><?= $lang ?></button>
<?php } ?>
</div>

<!-- Notes Content -->
<?php foreach($languages as $i=>$lang){
$notes = $pdo->query("SELECT * FROM notes WHERE language='$lang' ORDER BY created_at DESC");
?>
<div class="tabContent <?= $i==0?'':'hidden' ?>" id="tab-<?= $lang ?>">
<div class="grid gap-4">
<?php while($note=$notes->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="bg-white p-4 rounded-lg shadow-md animate__animated animate__fadeIn noteCard">
<div class="flex justify-between mb-2">
<h3 class="text-lg font-bold"><?= htmlspecialchars($note['title']); ?></h3>
</div>
<p><?= htmlspecialchars($note['content']); ?></p>
</div>
<?php } ?>
</div>
</div>
<?php } ?>

</main>

<script>
const tabBtns = document.querySelectorAll('.tabBtn');
const tabContents = document.querySelectorAll('.tabContent');
tabBtns.forEach(btn=>{
btn.addEventListener('click', ()=>{
tabBtns.forEach(b=>b.classList.remove('bg-green-600','text-white'));
btn.classList.add('bg-green-600','text-white');
tabContents.forEach(tc=>tc.classList.add('hidden'));
document.getElementById('tab-'+btn.dataset.tab).classList.remove('hidden');
});
});

document.getElementById('searchInput').addEventListener('input', e=>{
const q = e.target.value.toLowerCase();
document.querySelectorAll('.noteCard').forEach(card=>{
card.style.display = card.innerText.toLowerCase().includes(q)?'':'none';
});
});
</script>

</body>
</html>
