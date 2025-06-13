<?php
// Set the path to your Git repository

// Array of Git repository paths
$repoPaths = [
    '/home/miandrilala/htdocs/net.miandrilala.online/drupal'
    // Add more repository paths as needed
];

// Loop through each repository
foreach ($repoPaths as $repoPath) {
          // Set the branch you want to pull
        $branch = 'main';
        
        // Change directory to the Git repository
        chdir($repoPath);
        
        // Run the Git pull command
        $output = shell_exec("git pull origin $branch 2>&1");
        
        // Log the output (optional)
        //file_put_contents('git_pull.log', $output, FILE_APPEND);
        
       // echo "Git pull executed:\n$output";
}

?>
