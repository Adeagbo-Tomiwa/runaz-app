<?php
$current_page = basename($_SERVER['PHP_SELF'], ".php");
$current_page_title = ucfirst(str_replace("_", " ", $current_page));
?>
<nav class="bg-white dark:bg-gray-800 py-3 px-5 rounded-md w-full mt-10">
  <ol class="list-reset flex text-gray-700 text-sm">
    <li>
      <a href="index.php" class="text-blue-600 hover:text-blue-800">
        <i class="fa fa-home mr-1"></i> Home
      </a>
    </li>
    <li><span class="mx-2 text-gray-400">/</span></li>
    <li>
      <a href="javascript:history.back()" class="text-blue-600 hover:text-blue-800">
        <i class="fa fa-arrow-left mr-1"></i> Back
      </a>
    </li>
    <li><span class="mx-2 text-gray-400">/</span></li>
    <li class="text-gray-500 font-medium">
      <?php echo htmlspecialchars($current_page_title); ?>
    </li>
  </ol>
</nav>
