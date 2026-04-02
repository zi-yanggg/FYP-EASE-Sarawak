<?php
helper('translation');
$translationPayload = json_encode(ease_translation_payload(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>

<footer class="footer">
    <div class="footer-logo">
        <img src="<?= base_url('assets/images/Ease_PNG_File-01.png') ?>" alt="EASE SARAWAK Logo">
    </div>

    <hr>

    <ul class="footer-links">
        <li><a href="<?= base_url('/#services'); ?>">Our Services</a></li>
        <li><a href="<?= base_url('/#how'); ?>">How It Works</a></li>
        <li><a href="<?= base_url('/#why-choose-ease'); ?>">Why Us</a></li>
        <li><a href="<?= base_url('/#contact'); ?>">Contact Us</a></li>
        <li><a href="<?= base_url('/about'); ?>">About Us</a></li>
        <li><a href="<?= base_url('/policy'); ?>">Privacy Policy</a></li>
        <li><a href="<?= base_url('/terms-and-conditions'); ?>">Terms & Conditions</a></li>
    </ul>

    <div class="icons">
        <a href="https://instagram.com/yourusername" target="blank" title="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://tiktok.com/@yourusername" target="blank" title="TikTok">
            <i class="fab fa-tiktok"></i>
        </a>
        <a href="https://facebook.com/yourusername" target="blank" title="Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
    </div>

    <p class="footer-copy">© 2025 EASE SARAWAK. All rights reserved.</p>
</footer>

<button id="lang-toggle" onclick="toggleLangMenu()" class="lang-btn" type="button">🌐</button>
<div id="lang-options" class="lang-options">
    <button onclick="changeLanguage('en')" class="lang-option" type="button">EN</button>
    <button onclick="changeLanguage('zh')" class="lang-option" type="button">中文</button>
    <button onclick="changeLanguage('ms')" class="lang-option" type="button">BM</button>
</div>

<style>
        /* Floating Button Style */
        #lang-toggle {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 9999; /* Make sure it's above other content */
        }

        /* Language Options Style */
        .lang-options {
            display: none;
            position: fixed;
            bottom: 80px;
            left: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px;
            z-index: 9999;
        }

        .lang-option {
            background: none;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            display: block;
        }

        .lang-option:hover {
            background-color: #f0f0f0;
        }
</style>

<script>
    window.EASE_TRANSLATION = <?= $translationPayload ?>;

    function toggleLangMenu() {
        const langMenu = document.getElementById('lang-options');
        langMenu.style.display = langMenu.style.display === 'block' ? 'none' : 'block';
    }

    function changeLanguage(lang) {
        window.location.href = '<?= rtrim(base_url('language'), '/') ?>/' + encodeURIComponent(lang);
    }

    function easeTranslateValue(originalValue, map) {
    if (!originalValue || typeof originalValue !== 'string') {
        return originalValue;
    }

    // Normalize internal whitespace:
    // - turns newlines, tabs, multiple spaces into a single space
    // - keeps matching stable even if HTML is split across lines
    const normalized = originalValue.replace(/\s+/g, ' ').trim();

    if (!normalized || !map[normalized]) {
        return originalValue;
    }

    // Preserve leading/trailing whitespace from the original text node
    const leadingWhitespaceMatch = originalValue.match(/^\s*/);
    const trailingWhitespaceMatch = originalValue.match(/\s*$/);

    const leadingWhitespace = leadingWhitespaceMatch ? leadingWhitespaceMatch[0] : '';
    const trailingWhitespace = trailingWhitespaceMatch ? trailingWhitespaceMatch[0] : '';

    return leadingWhitespace + map[normalized] + trailingWhitespace;
    }

    function easeT(text) {
        const current = window.EASE_TRANSLATION?.current || 'en';
        const map = window.EASE_TRANSLATION?.translations?.[current] || {};
        return map[text] || text;
    }

    function applyEaseTranslations() {
        const current = window.EASE_TRANSLATION?.current || 'en';
        const map = window.EASE_TRANSLATION?.translations?.[current] || {};

        if (current === 'en' || !Object.keys(map).length) {
            document.documentElement.lang = current;
            return;
        }

        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
            acceptNode(node) {
                if (!node.nodeValue || !node.nodeValue.trim()) {
                    return NodeFilter.FILTER_REJECT;
                }

                const parentTag = node.parentElement ? node.parentElement.tagName : '';
                if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA'].includes(parentTag)) {
                    return NodeFilter.FILTER_REJECT;
                }

                return NodeFilter.FILTER_ACCEPT;
            }
        });

        const textNodes = [];
        while (walker.nextNode()) {
            textNodes.push(walker.currentNode);
        }

        textNodes.forEach((node) => {
            if (!node.__easeOriginalText) {
                node.__easeOriginalText = node.nodeValue;
            }
            node.nodeValue = easeTranslateValue(node.__easeOriginalText, map);
        });

        document.querySelectorAll('[placeholder]').forEach((element) => {
            if (!element.dataset.easeOriginalPlaceholder) {
                element.dataset.easeOriginalPlaceholder = element.getAttribute('placeholder');
            }

            const original = element.dataset.easeOriginalPlaceholder;
            if (map[original]) {
                element.setAttribute('placeholder', map[original]);
            }
        });

        document.querySelectorAll('input[type="button"], input[type="submit"], input[type="reset"]').forEach((element) => {
            if (!element.dataset.easeOriginalValue) {
                element.dataset.easeOriginalValue = element.value;
            }

            const original = element.dataset.easeOriginalValue;
            if (map[original]) {
                element.value = map[original];
            }
        });

        if (map[document.title]) {
            document.title = map[document.title];
        }

        document.documentElement.lang = current;
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyEaseTranslations();

        document.addEventListener('click', function (event) {
            const menu = document.getElementById('lang-options');
            const toggle = document.getElementById('lang-toggle');

            if (!menu || !toggle) {
                return;
            }

            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.style.display = 'none';
            }
        });
    });
</script>