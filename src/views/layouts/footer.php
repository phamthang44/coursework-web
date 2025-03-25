<?php

/**
 * Quora-style Footer Component
 * 
 * A reusable PHP component that creates a footer in the style of Quora using Tailwind CSS.
 * This file can be included in other PHP files.
 */

function render_quora_footer($additional_links = [], $show_newsletter = true)
{
    // Sanitize input array for security
    $sanitized_links = [];
    foreach ($additional_links as $title => $url) {
        $sanitized_links[htmlspecialchars($title, ENT_QUOTES, 'UTF-8')] = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }

    ob_start();
?>
    <footer class="bg-gray-50 border-t border-gray-200 mt-8 dark:bg-darkmode dark:border-gray-700 transition-colors duration-300">
        <!-- Newsletter Section (Optional) -->
        <?php if ($show_newsletter): ?>
            <div class="border-b border-gray-200">
                <div class="container mx-auto px-4 py-6">
                    <div class="max-w-2xl mx-auto text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 dark:text-gray-300">Thank you for being part of Quora!</h3>
                        <p class="text-gray-600 mb-4 text-sm dark:text-gray-300">Wishing you meaningful and inspiring answers.</p>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Footer Content -->
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h4 class="text-gray-800 font-semibold mb-4 dark:text-gray-300">About</h4>
                    <ul class="space-y-2">
                        <li><button class="about-knowledgehub text-gray-600 dark:text-gray-300  hover:text-red-600 dark:hover:text-red-400 text-sm">About KnowledgeHub</button></li>
                        <li><button class="privacy text-gray-600 dark:text-gray-300  hover:text-red-600 dark:hover:text-red-400 text-sm">Privacy</button></li>
                        <li><button class="terms text-gray-600 dark:text-gray-300  hover:text-red-600 dark:hover:text-red-400 text-sm">Terms</button></li>
                        <li><a href="/contact" class="text-gray-600 dark:text-gray-300  hover:text-red-600 dark:hover:text-red-400 text-sm">Contact</a></li>
                    </ul>
                </div>
                <!-- Community -->
                <div>
                    <h4 class="text-gray-800 font-semibold mb-4 dark:text-gray-300">Community</h4>
                    <ul class="space-y-2">
                        <li><button class="top-contributors text-gray-600 dark:text-gray-300  hover:text-red-600 dark:hover:text-red-400 text-sm">Top Contributors</button></li>
                        <li><button class="moderators text-gray-600 dark:text-gray-300  hover:text-red-600 dark:hover:text-red-400 text-sm">Moderators</button></li>
                    </ul>
                </div>
            </div>
            <!-- Bottom bar -->
            <div class="border-t border-gray-200 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-500 dark:text-gray-300 text-sm">
                    © <?php echo date('Y'); ?> Quora, Inc. All rights reserved.
                </div>
                <div class="flex gap-4 mt-4 md:mt-0">

                </div>
            </div>
        </div>

        <script>
            const aboutKnowledgeHub = document.querySelector('.about-knowledgehub');
            if (aboutKnowledgeHub) {
                aboutKnowledgeHub.addEventListener('click', () => {
                    const existingModal = document.querySelector('.modal-backdrop');
                    if (existingModal) {
                        existingModal.remove();
                    }
                    const aboutModal = new Modal();
                    aboutModal.openModal(`<p class="text-xl text-black dark:text-white">Knowledge Hub is an online platform where students can post questions and receive answers from their peers. It fosters collaborative learning by allowing students to share knowledge, discuss ideas, and help each other understand complex topics. This interactive environment encourages critical thinking and problem-solving while building a supportive academic community. Whether seeking homework help or exploring new concepts, students can benefit from diverse perspectives and real-time assistance. Knowledge Hub empowers learners by making education more accessible and engaging through peer-to-peer interaction.</p>`)
                });
            }
        </script>
    </footer>
<?php
    return ob_get_clean();
}
?>