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

<button id="lang-toggle" onclick="toggleLangMenu()" class="lang-btn" type="button">
    <img id="selected-flag" src="<?= base_url('assets/images/gb.png') ?>" alt="EN" class="lang-flag">
    <span id="selected-lang">EN</span>
    <span class="lang-caret">^</span>
</button>

<div id="lang-options" class="lang-options">
    <button onclick="changeLanguage('en')" class="lang-option" type="button">
        <img src="<?= base_url('assets/images/gb.png') ?>" alt="English" class="lang-flag">
        <span>English</span>
    </button>

    <button onclick="changeLanguage('zh')" class="lang-option" type="button">
        <img src="<?= base_url('assets/images/cn.png') ?>" alt="Chinese" class="lang-flag">
        <span>Chinese (Simplified)</span>
    </button>

    <button onclick="changeLanguage('ms')" class="lang-option" type="button">
        <img src="<?= base_url('assets/images/my.png') ?>" alt="Malay" class="lang-flag">
        <span>Malay</span>
    </button>
</div>

<style>
    #lang-toggle {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background-color: #fff;
        color: #333;
        border: 1px solid #d9d9d9;
        padding: 12px 16px;
        border-radius: 4px;
        font-size: 23px;
        font-weight: 600;
        cursor: pointer;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 130px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
    }

    .lang-options {
        display: none;
        position: fixed;
        bottom: 74px;
        left: 20px;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.14);
        border-radius: 4px;
        padding: 8px 0;
        z-index: 9999;
        min-width: 260px;
    }

    .lang-option {
        width: 100%;
        background: none;
        border: none;
        padding: 14px 16px;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        text-align: left;
        color: #333;
    }

    .lang-option:hover {
        background-color: #f5f5f5;
    }

    .lang-flag {
        width: 35px;
        height: 16px;
        object-fit: cover;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .lang-caret {
        margin-left: auto;
        font-size: 20px;
        color: #666;
    }
</style>

<script>
    window.EASE_TRANSLATION = <?= $translationPayload ?>;

    function getLangMeta(lang) {
        const base = '<?= base_url('assets/images/') ?>';

        if (lang === 'zh') {
            return { label: '中文', flag: base + 'cn.png' };
        }
        if (lang === 'ms') {
            return { label: 'BM', flag: base + 'my.png' };
        }
        return { label: 'EN', flag: base + 'gb.png' };
    }

    function updateLangButton(lang) {
        const meta = getLangMeta(lang);
        const selectedLang = document.getElementById('selected-lang');
        const selectedFlag = document.getElementById('selected-flag');

        if (selectedLang) {
            selectedLang.textContent = meta.label;
        }

        if (selectedFlag) {
            selectedFlag.src = meta.flag;
            selectedFlag.alt = meta.label;
        }
    }

    function toggleLangMenu() {
        const langMenu = document.getElementById('lang-options');
        const caret = document.querySelector('.lang-caret');

        if (!langMenu) return;

        if (langMenu.style.display === 'block') {
            langMenu.style.display = 'none';
            if (caret) caret.textContent = '^';
        } else {
            langMenu.style.display = 'block';
            if (caret) caret.textContent = '˅';
        }
    }

    function changeLanguage(lang) {
        localStorage.setItem('selectedLangCode', lang);
        updateLangButton(lang);
        window.location.href = '<?= rtrim(base_url('language'), '/') ?>/' + encodeURIComponent(lang);
    }

    function easeTranslateValue(originalValue, map) {
        if (!originalValue || typeof originalValue !== 'string') {
            return originalValue;
        }

        const normalized = originalValue.replace(/\s+/g, ' ').trim();
        if (!normalized || !map[normalized]) {
            return originalValue;
        }

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
        const current = window.EASE_TRANSLATION?.current || localStorage.getItem('selectedLangCode') || 'en';

        updateLangButton(current);
        applyEaseTranslations();

        document.addEventListener('click', function (event) {
            const menu = document.getElementById('lang-options');
            const toggle = document.getElementById('lang-toggle');
            const caret = document.querySelector('.lang-caret');

            if (!menu || !toggle) {
                return;
            }

            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.style.display = 'none';
                if (caret) caret.textContent = '^';
            }
        });
    });
</script>

<script>
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