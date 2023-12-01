<?php
// In your homepage code
include 'header.php';
use Services\SessionManager;    

// Check for success message session variable
$successMessage = SessionManager::getSessionVariable('success_message');
echo $successMessage;   
echo 'this is a testing of the user array in the session manger:';
$test=SessionManager::isLoggedIn();
if ($test) {
    echo $test;
    echo 'hi fron getSessionVariable [user][email]:'. SessionManager::getSessionVariable('user')['email'];

} else {
    echo 'false  -  you are not logged in';
}
//q: why is it now working? I managet to access the success_message variablebut not the [user][first_name] variable
//a: is there a problem with the way I am accessing the array?
//q: is there a problem with the way I am accessing the array?
//a: yes, you are using the wrong syntax. You should use SessionManager::getSessionVariable('user')['first_name']
//q: I changed it to echo 'hi fron getSessionVariable [user][email]:'. SessionManager::getSessionVariable('user')['email']; and it is still now working
//a: you are using the wrong syntax. You should use SessionManager::getSessionVariable('user')['email']
//q: but that's what I"ve used
//a: no, you are using SessionManager::getSessionVariable(['user']['email'])
//q: no, I'm using: 
/*
if (!empty($successMessage)) {
?>
   <div id="success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white w-full md:w-1/2 p-8 rounded shadow-md">
         <span class="cursor-pointer text-gray-500 absolute  z-10 top-2 right-2 text-xl" onclick="closeSuccessModal()">X</span>
         <p class="text-gray-500"><?php echo $successMessage; ?></p>
      </div>
   </div>
   <script defer>
      // JavaScript function to close the success modal
      function closeSuccessModal() {
         document.getElementById('success-modal').style.display = 'none';
      }
   </script>
<?php
   // Clear the session variable after displaying the success message
   SessionManager::setSessionVariable('success_message', '');
}*/
?>
  
    
        <!-- Main content section -->
        <main class="container mx-auto p-4">
            <!-- Pop CDs section -->
            <section>
                <h2 class="text-#13324E text-2xl font-bold mb-4">Pop CDs</h2>
        
                    <?php
                    // Include the "Pop CDs" section
                    $controller = new \Controllers\ProductController();
                $controller->showProductsByTag('pop');
            
                    ?>
        
            </section>
            <!-- Rock CDs section -->
            <section>
                <h2 class="text-#13324E text-2xl font-bold mb-4">Rock CDs</h2>
        
                    <?php
                    // Include the "Pop CDs" section
                    $controller = new \Controllers\ProductController();
                    $controller->showProductsByTag('rock');
        
                    ?>
        
            </section>
            <!-- Country CDs section -->
            <section>
                <h2 class="text-#13324E text-2xl font-bold mb-4">Country CDs</h2>
        
                    <?php
                        // Include the "CountryCDs" section
                        $controller = new \Controllers\ProductController();
                        $controller->showProductsByTag('country');   
                    ?>
        
            </section>


            <!-- New Releases section -->
            <section>
                <h2 class="text-#13324E text-2xl font-bold mt-8 mb-4">New Releases</h2>
            
                    <?php
                    // Include the "New Releases" section
                    $controller = new \Controllers\ProductController();
                    $controller->showRecentReleases();
                    ?>
            
            </section>

            <!-- Other content of your homepage -->
            <!-- ... -->
        </main>

        <!-- Footer section -->
        <?php
            include 'footer.php';
        ?>
    </body>
    </html>