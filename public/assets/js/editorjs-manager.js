/**
 * Editor.js Manager
 * A reusable component to initialize and manage Editor.js instances
 * 
 * Features:
 * - Native browser undo/redo (word-by-word) instead of block-level undo
 * - Optimized performance with debounced mutation observers
 * - Better browser compatibility and keyboard layout support
 * - Automatic cleanup on editor destruction
 */

/**
 * TwoColumnSection Tool for Editor.js
 * 
 * Data structure:
 * {
 *   text: string,
 *   image: { url: string, alt: string },
 *   reverse: boolean
 * }
 */
class TwoColumnSection {
    static get toolbox() {
        return {
            icon: '<svg width="20" height="20" viewBox="0 0 20 20"><path d="M17 1H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zM3 17V3h6v14H3zm14 0h-6V3h6v14z"/></svg>',
            title: 'Two Column'
        };
    }

    static get isReadOnlySupported() {
        return true;
    }

    static get enableLineBreaks() {
        return true;
    }

    static get sanitize() {
        return {
            text: {
                br: true, // Allow line breaks
                div: true, // Allow divs used by browsers for new lines
                b: true,
                i: true,
                u: true,
                a: {
                    href: true,
                    target: true,
                    rel: true
                },
                ul: true,
                ol: true,
                li: true, // Allow lists
                h2: true, // Allow headings
                h3: true,
                h4: true,
                span: {
                    class: true,
                    style: true
                }
            },
            reverse: false
        };
    }

    constructor({ data, api, config }) {
        this.api = api;
        this.config = config || {};
        this.data = {
            text: data.text || '',
            image: data.image || { url: '', alt: '' },
            reverse: data.reverse || false
        };
        this.nodes = {
            wrapper: null,
            textColumn: null,
            imagePreview: null,
            imageInput: null,
            uploadBtn: null
        };
    }

    render() {
        this.nodes.wrapper = document.createElement('div');
        this.nodes.wrapper.classList.add('cdx-two-column');

        if (this.data.reverse) {
            this.nodes.wrapper.classList.add('cdx-two-column--reverse');
        }

        // --- Text Column ---
        this.nodes.textColumn = document.createElement('div');
        this.nodes.textColumn.classList.add('cdx-two-column__text');
        this.nodes.textColumn.contentEditable = true;
        this.nodes.textColumn.innerHTML = this.data.text;
        this.nodes.textColumn.setAttribute('data-placeholder', 'Enter rich text content...');
        this.nodes.textColumn.style.whiteSpace = 'pre-wrap';

        // Fix Enter key for new lines and bullet points inside the text area
        this.nodes.textColumn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.stopPropagation();
            }
        });

        // Fix pasting text inside the text area
        this.nodes.textColumn.addEventListener('paste', (e) => {
            e.stopPropagation();
            e.preventDefault();

            let htmlData = e.clipboardData.getData('text/html');
            let textData = e.clipboardData.getData('text/plain');

            if (htmlData) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = htmlData;

                // Professional HTML cleanup to remove external styling and block formatting issues
                const cleanNode = (node) => {
                    const children = Array.from(node.childNodes);
                    for (const child of children) {
                        if (child.nodeType === Node.ELEMENT_NODE) {
                            // Preserve links
                            const isAnchor = child.tagName === 'A';
                            const href = isAnchor ? child.getAttribute('href') : null;

                            // Strip ALL attributes (classes, ids, inline styles) to stop external visual effects
                            while (child.attributes.length > 0) {
                                child.removeAttribute(child.attributes[0].name);
                            }

                            if (isAnchor && href) {
                                child.setAttribute('href', href);
                                child.setAttribute('target', '_blank');
                            }

                            // If it's an unwanted block/formatting tag, unwrap it to pure inline text
                            if (['DIV', 'P', 'SECTION', 'ARTICLE', 'PRE', 'CODE', 'SPAN', 'H1', 'H5', 'H6'].includes(child.tagName)) {
                                cleanNode(child);

                                // Add line breaks to simulate block spacing
                                if (['DIV', 'P', 'PRE', 'H1', 'H5', 'H6'].includes(child.tagName)) {
                                    node.insertBefore(document.createElement('br'), child);
                                }

                                // Move all children outside the unwrapped block
                                while (child.firstChild) {
                                    node.insertBefore(child.firstChild, child);
                                }
                                node.removeChild(child);
                            } else {
                                // Recursively clean allowed tags (like UL, LI, B, I, H2, H3, H4)
                                cleanNode(child);
                            }
                        }
                    }
                };

                cleanNode(tempDiv);

                // Insert the sanitized, unwrapped HTML perfectly into the cursor position
                document.execCommand('insertHTML', false, tempDiv.innerHTML);
            } else if (textData) {
                // Fallback for plain text copying
                const formattedHtml = textData.replace(/\r\n/g, '\n').replace(/\n/g, '<br>');
                document.execCommand('insertHTML', false, formattedHtml);
            }
        });

        // --- Image Column ---
        const imageColumn = document.createElement('div');
        imageColumn.classList.add('cdx-two-column__image');

        const imageWrapper = document.createElement('div');
        imageWrapper.classList.add('cdx-two-column__image-wrapper');

        this.nodes.imagePreview = document.createElement('img');
        this.nodes.imagePreview.classList.add('cdx-two-column__preview');

        this.nodes.uploadBtn = document.createElement('div');
        this.nodes.uploadBtn.classList.add('cdx-two-column__upload-btn');
        this.nodes.uploadBtn.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span>Upload Image</span>
        `;

        if (this.data.image.url) {
            this.nodes.imagePreview.src = this.data.image.url;
            this.nodes.imagePreview.style.display = 'block';
            this.nodes.uploadBtn.style.display = 'none';
            imageWrapper.classList.add('cdx-two-column__image-wrapper--filled');
        }

        imageWrapper.onclick = () => {
            if (!this.data.image.url) this.nodes.imageInput.click();
        };

        const removeBtn = document.createElement('div');
        removeBtn.classList.add('cdx-two-column__remove-btn');
        removeBtn.innerHTML = '×';
        removeBtn.onclick = (e) => {
            e.stopPropagation();
            this.data.image.url = '';
            this.nodes.imagePreview.src = '';
            this.nodes.imagePreview.style.display = 'none';
            this.nodes.uploadBtn.style.display = 'flex';
            imageWrapper.classList.remove('cdx-two-column__image-wrapper--filled');
        };

        this.nodes.imageInput = document.createElement('input');
        this.nodes.imageInput.type = 'file';
        this.nodes.imageInput.accept = 'image/*';
        this.nodes.imageInput.style.display = 'none';
        this.nodes.imageInput.addEventListener('change', (e) => this.uploadImage(e));

        imageWrapper.appendChild(this.nodes.imagePreview);
        imageWrapper.appendChild(this.nodes.uploadBtn);
        imageWrapper.appendChild(removeBtn);
        imageColumn.appendChild(imageWrapper);
        imageColumn.appendChild(this.nodes.imageInput);

        // Assembly
        this.nodes.wrapper.appendChild(this.nodes.textColumn);
        this.nodes.wrapper.appendChild(imageColumn);

        return this.nodes.wrapper;
    }

    async uploadImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        try {
            let url = '';
            // Use handler from config if provided, otherwise fallback to base64
            if (this.config.uploader && typeof this.config.uploader === 'function') {
                url = await this.config.uploader(file);
            } else {
                url = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => resolve(e.target.result);
                    reader.readAsDataURL(file);
                });
            }

            this.data.image.url = url;
            this.nodes.imagePreview.src = url;
            this.nodes.imagePreview.style.display = 'block';
            this.nodes.uploadBtn.style.display = 'none';
            this.nodes.wrapper.querySelector('.cdx-two-column__image-wrapper').classList.add('cdx-two-column__image-wrapper--filled');
        } catch (error) {
            console.error('TwoColumnSection: Image upload failed', error);
        }
    }

    save() {
        return {
            text: this.nodes.textColumn.innerHTML,
            image: {
                url: this.data.image.url,
                alt: this.data.image.alt || ''
            },
            reverse: this.data.reverse
        };
    }

    renderSettings() {
        const settings = [
            {
                name: 'reverse',
                icon: '<svg width="20" height="20" viewBox="0 0 20 20"><path d="M19 7l-3-3v2H5c-1.103 0-2 .897-2 2v3h2V8h11v2l3-3zM1 13l3 3v-2h11c1.103 0 2-.897 2-2V9h-2v3H4v-2l-3 3z"/></svg>',
                label: 'Reverse Layout',
                action: (button) => {
                    this.data.reverse = !this.data.reverse;
                    button.classList.toggle(this.api.styles.settingsButtonActive, this.data.reverse);
                    this.nodes.wrapper.classList.toggle('cdx-two-column--reverse', this.data.reverse);
                }
            },
            {
                name: 'bulletList',
                icon: '<svg width="20" height="20" viewBox="0 0 20 20"><path d="M7 5h12v2H7V5zm0 8h12v2H7v-2zm-4-8h2v2H3V5zm0 8h2v2H3v-2z"/></svg>',
                label: 'Toggle Bullet List',
                action: () => {
                    document.execCommand('insertUnorderedList', false, null);
                    this.nodes.textColumn.focus();
                }
            },
            {
                name: 'heading2',
                icon: '<svg width="20" height="20" viewBox="0 0 20 20"><text x="3" y="15" font-family="sans-serif" font-size="14" font-weight="bold">H2</text></svg>',
                label: 'Toggle Heading 2',
                action: () => {
                    const isHeading = document.queryCommandValue('formatBlock') === 'h2';
                    document.execCommand('formatBlock', false, isHeading ? 'div' : 'H2');
                    this.nodes.textColumn.focus();
                }
            },
            {
                name: 'heading3',
                icon: '<svg width="20" height="20" viewBox="0 0 20 20"><text x="3" y="15" font-family="sans-serif" font-size="14" font-weight="bold">H3</text></svg>',
                label: 'Toggle Heading 3',
                action: () => {
                    const isHeading = document.queryCommandValue('formatBlock') === 'h3';
                    document.execCommand('formatBlock', false, isHeading ? 'div' : 'H3');
                    this.nodes.textColumn.focus();
                }
            }
        ];

        const wrapper = document.createElement('div');

        settings.forEach(tune => {
            const button = document.createElement('div');
            button.classList.add(this.api.styles.settingsButton);
            if (tune.name === 'reverse') {
                button.classList.toggle(this.api.styles.settingsButtonActive, this.data.reverse);
            }
            button.innerHTML = tune.icon;

            this.api.tooltip.onHover(button, tune.label);

            const actionEvent = ['bulletList', 'heading2', 'heading3'].includes(tune.name) ? 'mousedown' : 'click';
            button.addEventListener(actionEvent, (e) => {
                if (actionEvent === 'mousedown') {
                    e.preventDefault();
                }
                tune.action(button);
            });

            wrapper.appendChild(button);
        });

        return wrapper;
    }
}

/**
 * Inline List Tool for Editor.js
 * Enables bullet point formatting for selected text in inline toolbars
 */
class InlineListTool {
    static get isInline() {
        return true;
    }

    static get sanitize() {
        return {
            ul: true,
            ol: true,
            li: true
        };
    }

    get state() {
        return this._state;
    }

    set state(state) {
        this._state = state;
        if (this.button) {
            this.button.classList.toggle(this.api.styles.inlineToolButtonActive, state);
        }
    }

    constructor({ api }) {
        this.api = api;
        this.button = null;
        this._state = false;
    }

    render() {
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.classList.add(this.api.styles.inlineToolButton);
        // Clean bullet list SVG
        this.button.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20"><path d="M7 5h12v2H7V5zm0 8h12v2H7v-2zm-4-8h2v2H3V5zm0 8h2v2H3v-2z"/></svg>';
        return this.button;
    }

    surround(range) {
        if (!range) {
            return;
        }
        document.execCommand('insertUnorderedList', false, null);
    }

    checkState(selection) {
        const text = selection.anchorNode;
        if (!text) {
            return;
        }
        const anchorElement = text instanceof Element ? text : text.parentElement;
        this.state = !!anchorElement.closest('ul') || !!anchorElement.closest('ol');
    }
}

class InlineHeading2Tool {
    static get isInline() { return true; }

    static get sanitize() {
        return {
            h2: true
        };
    }

    get state() { return this._state; }
    set state(state) {
        this._state = state;
        if (this.button) {
            this.button.classList.toggle(this.api.styles.inlineToolButtonActive, state);
        }
    }
    constructor({ api }) {
        this.api = api;
        this.button = null;
        this._state = false;
    }
    render() {
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.classList.add(this.api.styles.inlineToolButton);
        this.button.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20"><text x="3" y="15" font-family="sans-serif" font-size="14" font-weight="bold">H2</text></svg>';
        return this.button;
    }
    surround(range) {
        if (!range) return;
        let currentBlock = document.queryCommandValue('formatBlock');
        if (currentBlock) currentBlock = currentBlock.toLowerCase();

        const isHeading = currentBlock === 'h2';

        // Clear any existing block formats (h1-h6) to prevent nesting
        document.execCommand('formatBlock', false, 'div');

        if (!isHeading) {
            document.execCommand('formatBlock', false, 'H2');
        }
    }
    checkState(selection) {
        const text = selection.anchorNode;
        if (!text) return;
        const anchorElement = text instanceof Element ? text : text.parentElement;
        const nearestHeading = anchorElement.closest('h1, h2, h3, h4, h5, h6');
        this.state = nearestHeading ? nearestHeading.tagName.toLowerCase() === 'h2' : false;
    }
}

class InlineHeading3Tool {
    static get isInline() { return true; }

    static get sanitize() {
        return {
            h3: true
        };
    }

    get state() { return this._state; }
    set state(state) {
        this._state = state;
        if (this.button) {
            this.button.classList.toggle(this.api.styles.inlineToolButtonActive, state);
        }
    }
    constructor({ api }) {
        this.api = api;
        this.button = null;
        this._state = false;
    }
    render() {
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.classList.add(this.api.styles.inlineToolButton);
        this.button.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20"><text x="3" y="15" font-family="sans-serif" font-size="14" font-weight="bold">H3</text></svg>';
        return this.button;
    }
    surround(range) {
        if (!range) return;
        let currentBlock = document.queryCommandValue('formatBlock');
        if (currentBlock) currentBlock = currentBlock.toLowerCase();

        const isHeading = currentBlock === 'h3';

        // Clear any existing block formats (h1-h6) to prevent nesting
        document.execCommand('formatBlock', false, 'div');

        if (!isHeading) {
            document.execCommand('formatBlock', false, 'H3');
        }
    }
    checkState(selection) {
        const text = selection.anchorNode;
        if (!text) return;
        const anchorElement = text instanceof Element ? text : text.parentElement;
        const nearestHeading = anchorElement.closest('h1, h2, h3, h4, h5, h6');
        this.state = nearestHeading ? nearestHeading.tagName.toLowerCase() === 'h3' : false;
    }
}

// Prevent redeclaration
if (typeof EditorJSManager === 'undefined') {
    class EditorJSManager {
        constructor() {
            this.editors = {};
            this.undoInstances = {}; // Store undo instances
            this.undoObservers = {}; // Store mutation observers for cleanup
            this.undoHandlers = {}; // Store undo handlers for cleanup
            this.uploadEndpoint = document.querySelector('meta[name="editorjs-upload-url"]')?.content || '/admin/cms/upload-editor-image';
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        }

        /**
         * Initialize a new Editor.js instance
         * @param {string} holderId - ID of the container element (without #)
         * @param {object} options - Configuration options
         * @returns {EditorJS} - Editor.js instance
         */
        async init(holderId, options = {}) {
            const defaults = {
                holder: holderId,
                placeholder: options.placeholder || 'Start writing your content...',
                minHeight: options.minHeight || 200,
                autofocus: options.autofocus || false,
                data: options.data || {},
                tools: this.getTools(),
                // Note: Editor.js doesn't have a shortcuts config option in the main config
                // We'll handle keyboard events manually in enableNativeUndo
                onChange: async () => {
                    if (options.onChange) {
                        const data = await this.editors[holderId].save();
                        options.onChange(data);
                    }
                },
                onReady: () => {
                    if (options.onReady) {
                        options.onReady();
                    }
                    // Enable drag and drop reordering
                    if (typeof DragDrop !== 'undefined') {
                        new DragDrop(this.editors[holderId]);
                    }

                    // Enable native browser undo/redo for word-by-word editing
                    // This allows Ctrl+Z to work character-by-character or word-by-word
                    // instead of block-by-block (which is Editor.js undo plugin's default behavior)
                    // Use requestAnimationFrame + setTimeout for better timing
                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            this.enableNativeUndo(holderId);
                        }, 50); // Reduced delay since we're using RAF
                    });
                }
            };

            try {
                this.editors[holderId] = new EditorJS(defaults);
                await this.editors[holderId].isReady;
                return this.editors[holderId];
            } catch (error) {
                console.error('Editor.js initialization failed:', error);
                throw error;
            }
        }

        /**
         * Get tools configuration for Editor.js
         */
        getTools() {
            const manager = this;

            const tools = {
                inlineList: InlineListTool,
                inlineH2: InlineHeading2Tool,
                inlineH3: InlineHeading3Tool,
                twoColumn: {
                    class: TwoColumnSection,
                    inlineToolbar: true,
                    config: {
                        uploader: (file) => manager.uploadImage(file).then(res => res.file.url)
                    }
                }
            };

            // Keep the same rich inline toolbar in Homepage, Platforms and Blogs.
            const inlineTools = ['link', 'bold', 'italic', 'inlineH2', 'inlineH3', 'inlineList'];
            if (typeof window.FontSizeTool !== 'undefined') inlineTools.push('fontSize');
            if (typeof window.FontFamilyTool !== 'undefined') inlineTools.push('fontFamily');
            if (typeof Marker !== 'undefined') inlineTools.push('marker');
            if (typeof InlineCode !== 'undefined') inlineTools.push('inlineCode');
            if (typeof Underline !== 'undefined') inlineTools.push('underline');

            if (typeof Paragraph !== 'undefined') {
                tools.paragraph = {
                    class: Paragraph,
                    inlineToolbar: inlineTools
                };
            }

            if (typeof Header !== 'undefined') {
                tools.header = {
                    class: Header,
                    config: { placeholder: 'Enter a header', levels: [2, 3, 4, 5, 6], defaultLevel: 2 },
                    inlineToolbar: inlineTools,
                    shortcut: 'CMD+SHIFT+H'
                };
            }
            if (typeof window.FontSizeTool !== 'undefined') {
                tools.fontSize = window.FontSizeTool;
            } else {
                console.warn("FontSizeTool is not defined on window");
            }
            if (typeof window.FontFamilyTool !== 'undefined') {
                tools.fontFamily = window.FontFamilyTool;
            } else {
                console.warn("FontFamilyTool is not defined on window");
            }
            if (typeof window.List !== 'undefined' || typeof window.NestedList !== 'undefined') {
                tools.list = {
                    class: window.List || window.NestedList,
                    inlineToolbar: true,
                    config: { defaultStyle: 'unordered' },
                    shortcut: 'CMD+SHIFT+L'
                };
            }
            if (typeof window.Checklist !== 'undefined') {
                tools.checklist = { class: window.Checklist, inlineToolbar: true };
            }
            if (typeof Quote !== 'undefined') {
                tools.quote = {
                    class: Quote,
                    inlineToolbar: true,
                    config: { quotePlaceholder: 'Enter a quote', captionPlaceholder: 'Quote\'s author' },
                    shortcut: 'CMD+SHIFT+Q'
                };
            }
            if (typeof Delimiter !== 'undefined') {
                tools.delimiter = Delimiter;
            }
            if (typeof Table !== 'undefined') {
                tools.table = { class: Table, inlineToolbar: true, config: { rows: 2, cols: 3 } };
            }
            if (typeof ImageTool !== 'undefined') {
                tools.image = {
                    class: ImageTool,
                    config: {
                        endpoints: { byFile: manager.uploadEndpoint },
                        additionalRequestHeaders: { 'X-CSRF-TOKEN': manager.csrfToken },
                        field: 'image',
                        types: 'image/*',
                        captionPlaceholder: 'Image caption',
                        buttonContent: 'Select an image',
                        uploader: {
                            uploadByFile(file) { return manager.uploadImage(file); }
                        }
                    }
                };
            }
            if (typeof Embed !== 'undefined') {
                tools.embed = { class: Embed, config: { services: { youtube: true, vimeo: true, twitter: true, codepen: true } } };
            }
            if (typeof CodeTool !== 'undefined') {
                tools.code = { class: CodeTool, shortcut: 'CMD+SHIFT+C' };
            }
            if (typeof RawTool !== 'undefined') {
                tools.raw = RawTool;
            }
            if (typeof LinkTool !== 'undefined') {
                tools.linkTool = { class: LinkTool, config: { endpoint: '/admin/cms/fetch-url-metadata' } };
            }
            if (typeof Marker !== 'undefined') {
                tools.marker = { class: Marker, shortcut: 'CMD+SHIFT+M' };
            }
            if (typeof Underline !== 'undefined') {
                tools.underline = Underline;
            }
            if (typeof InlineCode !== 'undefined') {
                tools.inlineCode = { class: InlineCode, shortcut: 'CMD+SHIFT+K' };
            }
            if (typeof Warning !== 'undefined') {
                tools.warning = { class: Warning, inlineToolbar: true, config: { titlePlaceholder: 'Title', messagePlaceholder: 'Message' } };
            }
            if (typeof AttachesTool !== 'undefined') {
                tools.attaches = { class: AttachesTool, config: { endpoint: manager.uploadEndpoint, additionalRequestHeaders: { 'X-CSRF-TOKEN': manager.csrfToken } } };
            }

            return tools;
        }

        /**
         * Enable native browser undo/redo for word-by-word editing
         * This allows Ctrl+Z to work character-by-character or word-by-word
         * instead of block-by-block
         * 
         * Improved version with:
         * - Better performance (debounced mutation observer)
         * - More reliable event handling
         * - Better browser compatibility
         * - Support for multiple keyboard layouts
         */
        enableNativeUndo(holderId) {
            const editor = this.editors[holderId];
            if (!editor) {
                console.warn('Editor instance not found for:', holderId);
                return;
            }

            // Find the editor container - Editor.js creates a wrapper div
            const editorElement = document.getElementById(holderId);
            if (!editorElement) {
                console.warn('Editor element not found:', holderId);
                return;
            }

            // Wait for Editor.js to fully render
            const findRedactor = () => {
                return editorElement.querySelector('.codex-editor__redactor') ||
                    editorElement.querySelector('[class*="redactor"]');
            };

            let redactorElement = findRedactor();
            if (!redactorElement) {
                // Retry after a short delay if redactor not found immediately
                setTimeout(() => {
                    redactorElement = findRedactor();
                    if (redactorElement) {
                        this.setupNativeUndo(holderId, editor, editorElement, redactorElement);
                    } else {
                        console.warn('Editor.js redactor not found after retry for:', holderId);
                    }
                }, 200);
                return;
            }

            this.setupNativeUndo(holderId, editor, editorElement, redactorElement);
        }

        /**
         * Setup native undo handlers for a specific editor instance
         */
        setupNativeUndo(holderId, editor, editorElement, redactorElement) {
            // Track if we're already set up for this editor
            if (this.undoHandlers && this.undoHandlers[holderId]) {
                console.log('Native undo already enabled for:', holderId);
                return;
            }

            // Cache for contentEditable elements to avoid repeated DOM queries
            const editableCache = new WeakSet();

            // Optimized function to check if we're in a contentEditable block
            const isInContentEditable = (target) => {
                if (!target) return false;

                // Use cache if available
                if (editableCache.has(target)) return true;

                // Fast path: check target itself
                if (target.isContentEditable === true) {
                    editableCache.add(target);
                    return true;
                }

                // Check attribute (faster than closest for immediate element)
                if (target.getAttribute && target.getAttribute('contenteditable') === 'true') {
                    editableCache.add(target);
                    return true;
                }

                // Check if target is inside a contentEditable element
                const contentEditableParent = target.closest('[contenteditable="true"]');
                if (contentEditableParent) {
                    editableCache.add(target);
                    return true;
                }

                // Check if target is inside Editor.js block content
                const blockContent = target.closest('.ce-block__content');
                if (blockContent) {
                    const editable = blockContent.querySelector('[contenteditable="true"]');
                    if (editable) {
                        editableCache.add(target);
                        return true;
                    }
                }

                return false;
            };

            // Enhanced keyboard detection for better compatibility
            const isUndoKey = (e) => {
                const key = e.key?.toLowerCase();
                const code = e.code?.toLowerCase();
                const keyCode = e.keyCode;
                const isModifier = e.ctrlKey || e.metaKey;
                const isShift = e.shiftKey;

                // Check multiple ways to detect undo
                return isModifier && !isShift && (
                    key === 'z' ||
                    code === 'keyz' ||
                    keyCode === 90 ||
                    (e.which === 90)
                );
            };

            const isRedoKey = (e) => {
                const key = e.key?.toLowerCase();
                const code = e.code?.toLowerCase();
                const keyCode = e.keyCode;
                const isModifier = e.ctrlKey || e.metaKey;
                const isShift = e.shiftKey;

                // Check for Ctrl+Y or Ctrl+Shift+Z
                return isModifier && (
                    (key === 'y' || code === 'keyy' || keyCode === 89 || e.which === 89) ||
                    (isShift && (key === 'z' || code === 'keyz' || keyCode === 90 || e.which === 90))
                );
            };

            // Optimized event handler with early returns
            const handleKeyDown = (e) => {
                // Early return if not undo/redo keys
                const isUndo = isUndoKey(e);
                const isRedo = isRedoKey(e);

                if (!isUndo && !isRedo) return;

                const target = e.target;

                // Early return if not in contentEditable
                if (!isInContentEditable(target)) return;

                // CRITICAL: Stop all propagation immediately
                e.stopImmediatePropagation();
                e.stopPropagation();
                e.preventDefault();

                // Execute native browser undo/redo command
                try {
                    const command = isUndo ? 'undo' : 'redo';
                    const success = document.execCommand(command, false, null);

                    if (!success) {
                        // Fallback: try to trigger native browser undo via input event
                        const activeElement = document.activeElement;
                        if (activeElement && activeElement.isContentEditable) {
                            // Create input event to trigger browser's native undo stack
                            const inputEvent = new InputEvent('input', {
                                bubbles: true,
                                cancelable: true,
                                inputType: isUndo ? 'historyUndo' : 'historyRedo'
                            });
                            activeElement.dispatchEvent(inputEvent);
                        }
                    }
                } catch (err) {
                    // Silent fail - browser will handle it naturally if execCommand fails
                    console.debug('Native undo/redo execution:', err.message);
                }

                return false; // Indicate we handled the event
            };

            // Create a scoped handler that only applies to this editor
            const scopedHandler = (e) => {
                // Only handle events within this editor's scope
                if (!editorElement.contains(e.target)) return;
                return handleKeyDown(e);
            };

            // Add event listeners with highest priority (capture phase)
            const listenerOptions = {
                capture: true,
                passive: false
            };

            // Document-level listener (highest priority)
            document.addEventListener('keydown', scopedHandler, listenerOptions);

            // Editor-level listeners (backup)
            redactorElement.addEventListener('keydown', scopedHandler, listenerOptions);
            editorElement.addEventListener('keydown', scopedHandler, listenerOptions);

            // Debounced mutation observer for better performance
            let mutationTimeout;
            const observer = new MutationObserver(() => {
                // Debounce to avoid excessive DOM queries
                clearTimeout(mutationTimeout);
                mutationTimeout = setTimeout(() => {
                    // Refresh cache and ensure all contentEditable elements have handlers
                    const contentEditables = redactorElement.querySelectorAll('[contenteditable="true"]');
                    contentEditables.forEach(element => {
                        // Add to cache
                        editableCache.add(element);
                        // Ensure handler is attached
                        element.removeEventListener('keydown', scopedHandler, true);
                        element.addEventListener('keydown', scopedHandler, listenerOptions);
                    });
                }, 100); // Debounce delay
            });

            // Observe the editor for changes
            observer.observe(redactorElement, {
                childList: true,
                subtree: true,
                attributes: false, // Don't observe attributes for performance
                characterData: false // Don't observe text changes
            });

            // Store references for cleanup
            if (!this.undoObservers) {
                this.undoObservers = {};
            }
            this.undoObservers[holderId] = {
                observer: observer,
                timeout: mutationTimeout
            };

            if (!this.undoHandlers) {
                this.undoHandlers = {};
            }
            this.undoHandlers[holderId] = {
                handler: scopedHandler,
                redactor: redactorElement,
                container: editorElement,
                document: document,
                cache: editableCache
            };

            console.log('✅ Native word-by-word undo/redo enabled for editor:', holderId);
        }

        /**
         * Check if native undo/redo is supported by the browser
         * @returns {boolean}
         */
        isNativeUndoSupported() {
            try {
                // Check if execCommand is available
                return typeof document.execCommand === 'function';
            } catch (e) {
                return false;
            }
        }

        /**
         * Upload image to server
         */
        async uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch(this.uploadEndpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success === 1) {
                    return data;
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            } catch (error) {
                console.error('Image upload error:', error);
                throw error;
            }
        }

        /**
         * Get editor instance by ID
         */
        get(holderId) {
            return this.editors[holderId];
        }

        /**
         * Save editor data
         */
        async save(holderId) {
            if (this.editors[holderId]) {
                return await this.editors[holderId].save();
            }
            return null;
        }

        /**
         * Destroy editor instance
         */
        destroy(holderId) {
            if (this.editors[holderId]) {
                this.editors[holderId].destroy();
                delete this.editors[holderId];
            }
            // Clean up undo instance
            if (this.undoInstances && this.undoInstances[holderId]) {
                delete this.undoInstances[holderId];
            }
            // Clean up mutation observer
            if (this.undoObservers && this.undoObservers[holderId]) {
                this.undoObservers[holderId].disconnect();
                delete this.undoObservers[holderId];
            }
            // Clean up event handlers
            if (this.undoHandlers && this.undoHandlers[holderId]) {
                const handlerData = this.undoHandlers[holderId];
                try {
                    if (handlerData.document) {
                        handlerData.document.removeEventListener('keydown', handlerData.handler, true);
                    }
                    if (handlerData.redactor) {
                        handlerData.redactor.removeEventListener('keydown', handlerData.handler, true);
                    }
                    if (handlerData.container) {
                        handlerData.container.removeEventListener('keydown', handlerData.handler, true);
                    }
                    // Clear cache
                    if (handlerData.cache) {
                        // WeakSet doesn't need explicit clearing, but we can null it
                        handlerData.cache = null;
                    }
                } catch (err) {
                    console.warn('Error cleaning up undo handlers:', err);
                }
                delete this.undoHandlers[holderId];
            }

            // Clean up mutation observer timeout
            if (this.undoObservers && this.undoObservers[holderId]) {
                const observerData = this.undoObservers[holderId];
                try {
                    if (observerData.observer) {
                        observerData.observer.disconnect();
                    }
                    if (observerData.timeout) {
                        clearTimeout(observerData.timeout);
                    }
                } catch (err) {
                    console.warn('Error cleaning up undo observer:', err);
                }
                delete this.undoObservers[holderId];
            }
        }

        /**
         * Destroy all editor instances
         */
        destroyAll() {
            Object.keys(this.editors).forEach(holderId => {
                this.destroy(holderId);
            });
        }

        /**
         * Convert Editor.js JSON to HTML
         */
        toHTML(data) {
            if (!data || !data.blocks) {
                return '';
            }

            let html = '';

            data.blocks.forEach(block => {
                switch (block.type) {
                    case 'header':
                        html += `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
                        break;
                    case 'paragraph':
                        html += `<p>${block.data.text}</p>`;
                        break;
                    case 'list':
                        const listTag = block.data.style === 'ordered' ? 'ol' : 'ul';

                        // Helper function to render list items (handles nested lists too)
                        const renderListItems = (items, isOrdered) => {
                            if (!items || !Array.isArray(items) || items.length === 0) {
                                return '';
                            }

                            let listHtml = '';
                            items.forEach(item => {
                                // Handle both string items and object items (NestedList format)
                                let itemContent = '';
                                let nestedItems = [];

                                if (typeof item === 'string') {
                                    itemContent = item;
                                } else if (typeof item === 'object' && item !== null) {
                                    itemContent = item.content || item.text || '';
                                    nestedItems = item.items || [];
                                }

                                // Only add non-empty items
                                if (itemContent && itemContent.trim() !== '') {
                                    listHtml += `<li>${itemContent}`;

                                    // Handle nested list items
                                    if (nestedItems && nestedItems.length > 0) {
                                        const nestedTag = isOrdered ? 'ol' : 'ul';
                                        listHtml += `<${nestedTag}>${renderListItems(nestedItems, isOrdered)}</${nestedTag}>`;
                                    }

                                    listHtml += `</li>`;
                                }
                            });
                            return listHtml;
                        };

                        const listItems = renderListItems(block.data.items, block.data.style === 'ordered');
                        if (listItems) {
                            html += `<${listTag}>${listItems}</${listTag}>`;
                        }
                        break;
                    case 'quote':
                        html += `<blockquote><p>${block.data.text}</p><cite>${block.data.caption}</cite></blockquote>`;
                        break;
                    case 'delimiter':
                        html += '<hr>';
                        break;
                    case 'table':
                        let tableHtml = '<table class="table table-bordered">';
                        block.data.content.forEach((row, index) => {
                            tableHtml += '<tr>';
                            row.forEach(cell => {
                                const tag = index === 0 ? 'th' : 'td';
                                tableHtml += `<${tag}>${cell}</${tag}>`;
                            });
                            tableHtml += '</tr>';
                        });
                        tableHtml += '</table>';
                        html += tableHtml;
                        break;
                    case 'image': {
                        const imgData = block.data || {};
                        const imgUrl = imgData.file ? imgData.file.url : '';
                        const imgCaption = imgData.caption || '';
                        const withBorder = imgData.withBorder || false;
                        const withBackground = imgData.withBackground || false;
                        const stretched = imgData.stretched || false;

                        // Build style attributes for image - RESPONSIVE
                        let imgStyle = 'max-width: 100%; width: auto; height: auto; display: block; margin: 0 auto; ';
                        if (withBorder) {
                            imgStyle += 'border: 1px solid #e8e8eb; ';
                        }
                        if (withBackground) {
                            imgStyle += 'background: #eff2f5; padding: 10px; ';
                        }
                        if (stretched) {
                            imgStyle += 'width: 100%; ';
                        }

                        // Build style for figure container - RESPONSIVE with max-width
                        let figureStyle = 'max-width: 700px; width: 100%; margin: 20px auto; text-align: center; display: block; ';
                        if (withBackground) {
                            figureStyle += 'background: #eff2f5; padding: 15px; border-radius: 4px; ';
                        }
                        if (stretched) {
                            figureStyle += 'max-width: 100%; ';
                        }

                        // Add data attributes to preserve settings
                        let dataAttrs = '';
                        dataAttrs += `data-with-border="${withBorder}" `;
                        dataAttrs += `data-with-background="${withBackground}" `;
                        dataAttrs += `data-stretched="${stretched}"`;

                        const imgStyleAttr = imgStyle ? ` style="${imgStyle.trim()}"` : '';
                        const figureStyleAttr = figureStyle ? ` style="${figureStyle.trim()}"` : '';
                        html += `<figure class="editorjs-image"${dataAttrs}${figureStyleAttr}><img src="${imgUrl}" alt="${imgCaption}"${imgStyleAttr}><figcaption style="margin-top: 10px; font-size: 0.9em; color: #666; text-align: center;">${imgCaption}</figcaption></figure>`;
                        break;
                    }
                    case 'code':
                        html += `<pre><code>${this.escapeHtml(block.data.code)}</code></pre>`;
                        break;
                    case 'raw':
                        html += block.data.html;
                        break;
                    case 'embed':
                        html += `<div class="embed-responsive embed-responsive-16by9">${block.data.embed}</div>`;
                        break;
                    case 'warning':
                        html += `<div class="alert alert-warning"><strong>${block.data.title}</strong><p>${block.data.message}</p></div>`;
                        break;
                    case 'checklist':
                        const checkItems = block.data.items.map(item =>
                            `<li><input type="checkbox" ${item.checked ? 'checked' : ''} disabled> ${item.text}</li>`
                        ).join('');
                        html += `<ul class="checklist">${checkItems}</ul>`;
                        break;
                    case 'linkTool':
                        // Handle LinkTool blocks
                        const linkData = block.data || {};
                        const linkUrl = linkData.link || '';
                        const linkMeta = linkData.meta || {};
                        const linkTitle = linkMeta.title || linkUrl;
                        const linkDescription = linkMeta.description || '';
                        const linkImage = linkMeta.image ? (linkMeta.image.url || '') : '';

                        // Escape URL for HTML attribute (escape quotes and ampersands)
                        const escapedUrl = linkUrl.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                        const escapedTitle = this.escapeHtml(linkTitle);
                        const escapedDescription = this.escapeHtml(linkDescription);
                        const escapedImage = linkImage.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');

                        let linkHtml = '<div class="link-tool">';
                        if (linkImage) {
                            linkHtml += `<div class="link-tool-image"><img src="${escapedImage}" alt="${escapedTitle}"></div>`;
                        }
                        linkHtml += `<div class="link-tool-content">`;
                        linkHtml += `<a href="${escapedUrl}" target="_blank" rel="noopener noreferrer" class="link-tool-link">${escapedTitle}</a>`;
                        if (linkDescription) {
                            linkHtml += `<p class="link-tool-description">${escapedDescription}</p>`;
                        }
                        linkHtml += `</div></div>`;
                        html += linkHtml;
                        break;
                    case 'twoColumn': {
                        const twoColData = block.data || {};
                        const text = twoColData.text || '';
                        const image = twoColData.image || {};
                        const imgUrl = image.url || '';
                        const imgAlt = image.alt || '';
                        const isReverse = twoColData.reverse || false;

                        html += `
                            <div class="cdx-two-column ${isReverse ? 'cdx-two-column--reverse' : ''}">
                                <div class="cdx-two-column__text">${text}</div>
                                <div class="cdx-two-column__image">
                                    <div class="cdx-two-column__image-wrapper">
                                        <img src="${imgUrl}" alt="${imgAlt}" class="cdx-two-column__preview" style="display: block;">
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                    }
                    default:
                        console.warn('Unknown block type:', block.type);
                }
            });

            return html;
        }

        /**
         * Convert HTML to Editor.js JSON (basic conversion)
         */
        fromHTML(html) {
            if (!html || html === '') {
                return { blocks: [] };
            }

            const htmlTrimmed = html.trim();
            const blocks = [];

            // Extract script/style/noscript tags from HTML before parsing
            const parts = [];
            let lastIndex = 0;
            const tagRegex = /<(script|style|noscript)[^>]*>[\s\S]*?<\/\1>/gi;
            let match;

            while ((match = tagRegex.exec(htmlTrimmed)) !== null) {
                if (match.index > lastIndex) {
                    const beforeContent = htmlTrimmed.substring(lastIndex, match.index);
                    if (beforeContent.trim()) {
                        parts.push({ type: 'html', content: beforeContent });
                    }
                }
                parts.push({ type: 'raw', content: match[0] });
                lastIndex = match.index + match[0].length;
            }

            if (lastIndex < htmlTrimmed.length) {
                const afterContent = htmlTrimmed.substring(lastIndex);
                if (afterContent.trim()) {
                    parts.push({ type: 'html', content: afterContent });
                }
            }

            if (parts.length === 0) {
                parts.push({ type: 'html', content: htmlTrimmed });
            }

            parts.forEach(part => {
                if (part.type === 'raw') {
                    blocks.push({
                        type: 'raw',
                        data: { html: part.content }
                    });
                } else {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = part.content;

                    // Process all child nodes (including text nodes)
                    Array.from(tempDiv.childNodes).forEach(node => {
                        // Handle Text Nodes
                        if (node.nodeType === Node.TEXT_NODE) {
                            const text = node.textContent.trim();
                            if (text) {
                                blocks.push({
                                    type: 'paragraph',
                                    data: { text: text }
                                });
                            }
                            return;
                        }

                        // Handle Comment Nodes (ignore)
                        if (node.nodeType !== Node.ELEMENT_NODE) return;

                        const element = node;
                        const tagName = element.tagName.toLowerCase();

                        // Check if it's a "Complex" element that should be kept as RAW
                        // Factors: classes, id, style, custom data attributes, or tags we don't have block tools for
                        const supportedNativeBlocks = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'ul', 'ol', 'blockquote', 'figure', 'img', 'pre', 'hr', 'table', 'div'];
                        const isUnknownTag = !supportedNativeBlocks.includes(tagName);

                        // If it has a complex nested structure (e.g. div inside a p or section), treat as raw
                        const hasComplexNesting = element.querySelectorAll('div, section, article, table, header, footer, aside, nav').length > 0;

                        // Special case: if it has custom classes or styles on common tags, we MUST preserve them via Raw block
                        // because Editor.js standard blocks (paragraph, header) discard attributes on re-save.
                        const hasCustomStyles = (tagName === 'p' || tagName.startsWith('h')) &&
                            (element.classList.length > 0 || element.getAttribute('style') || element.getAttribute('id'));

                        if (isUnknownTag || hasComplexNesting || hasCustomStyles) {
                            blocks.push({
                                type: 'raw',
                                data: { html: element.outerHTML }
                            });
                            return;
                        }

                        // Standard block conversion
                        switch (tagName) {
                            case 'h1':
                            case 'h2':
                            case 'h3':
                            case 'h4':
                            case 'h5':
                            case 'h6':
                                blocks.push({
                                    type: 'header',
                                    data: {
                                        text: element.innerHTML,
                                        level: parseInt(tagName[1])
                                    }
                                });
                                break;
                            case 'p':
                                const imgInP = element.querySelector('img');
                                if (imgInP && element.children.length === 1 && element.textContent.trim() === '') {
                                    blocks.push({
                                        type: 'image',
                                        data: {
                                            file: { url: imgInP.getAttribute('src') || '' },
                                            caption: imgInP.getAttribute('alt') || '',
                                            withBorder: imgInP.style.border !== '' || element.classList.contains('with-border'),
                                            withBackground: imgInP.style.background !== '' || element.classList.contains('with-background'),
                                            stretched: imgInP.style.width === '100%' || element.classList.contains('stretched')
                                        }
                                    });
                                } else {
                                    blocks.push({
                                        type: 'paragraph',
                                        data: { text: element.innerHTML }
                                    });
                                }
                                break;
                            case 'ul':
                            case 'ol':
                                const parseListItems = (listElement) => {
                                    const parsedItems = [];
                                    const directChildren = Array.from(listElement.children).filter(child => child.tagName.toLowerCase() === 'li');
                                    directChildren.forEach(li => {
                                        let nestedItems = [];
                                        const liClone = li.cloneNode(true);
                                        const nestedLists = liClone.querySelectorAll('ul, ol');
                                        nestedLists.forEach(nestedList => {
                                            nestedItems = nestedItems.concat(parseListItems(nestedList));
                                            nestedList.remove();
                                        });
                                        const itemContent = (liClone.innerHTML || liClone.textContent || '').trim();
                                        if (itemContent && itemContent !== '[object Object]' && itemContent !== 'undefined' && itemContent !== 'null') {
                                            parsedItems.push({ content: itemContent, items: nestedItems });
                                        }
                                    });
                                    return parsedItems;
                                };
                                const listItems = parseListItems(element);
                                if (listItems.length > 0) {
                                    blocks.push({
                                        type: 'list',
                                        data: {
                                            style: tagName === 'ol' ? 'ordered' : 'unordered',
                                            items: listItems
                                        }
                                    });
                                }
                                break;
                            case 'blockquote':
                                blocks.push({
                                    type: 'quote',
                                    data: {
                                        text: element.querySelector('p')?.innerHTML || element.innerHTML,
                                        caption: element.querySelector('cite')?.innerHTML || ''
                                    }
                                });
                                break;
                            case 'figure':
                                const figImg = element.querySelector('img');
                                const figcaption = element.querySelector('figcaption');
                                if (figImg) {
                                    blocks.push({
                                        type: 'image',
                                        data: {
                                            file: { url: figImg.getAttribute('src') || '' },
                                            caption: (figcaption ? figcaption.textContent : figImg.getAttribute('alt')) || '',
                                            withBorder: element.getAttribute('data-with-border') === 'true' || figImg.style.border !== '',
                                            withBackground: element.getAttribute('data-with-background') === 'true' || figImg.style.background !== '',
                                            stretched: element.getAttribute('data-stretched') === 'true' || figImg.style.width === '100%'
                                        }
                                    });
                                }
                                break;
                            case 'img':
                                blocks.push({
                                    type: 'image',
                                    data: {
                                        file: { url: element.getAttribute('src') || '' },
                                        caption: element.getAttribute('alt') || '',
                                        withBorder: element.style.border !== '',
                                        withBackground: element.style.background !== '',
                                        stretched: element.style.width === '100%'
                                    }
                                });
                                break;
                            case 'pre':
                                blocks.push({
                                    type: 'code',
                                    data: { code: element.querySelector('code')?.textContent || element.textContent }
                                });
                                break;
                            case 'hr':
                                blocks.push({ type: 'delimiter', data: {} });
                                break;
                            case 'table':
                                const rows = Array.from(element.querySelectorAll('tr')).map(tr => {
                                    return Array.from(tr.querySelectorAll('td, th')).map(td => td.innerHTML);
                                });
                                if (rows.length > 0) {
                                    blocks.push({
                                        type: 'table',
                                        data: { content: rows, withHeadings: element.querySelector('th') !== null }
                                    });
                                }
                                break;
                            case 'div':
                                if (element.classList.contains('cdx-two-column')) {
                                    const textElement = element.querySelector('.cdx-two-column__text');
                                    const imageElement = element.querySelector('.cdx-two-column__preview');
                                    const isReverse = element.classList.contains('cdx-two-column--reverse');

                                    blocks.push({
                                        type: 'twoColumn',
                                        data: {
                                            text: textElement ? textElement.innerHTML : '',
                                            image: {
                                                url: imageElement ? imageElement.getAttribute('src') : '',
                                                alt: imageElement ? imageElement.getAttribute('alt') : ''
                                            },
                                            reverse: isReverse
                                        }
                                    });
                                    break;
                                }

                                if (element.classList.contains('link-tool')) {
                                    const linkElement = element.querySelector('.link-tool-link') || element.querySelector('a');
                                    const linkImage = element.querySelector('.link-tool-image img');
                                    const linkDescription = element.querySelector('.link-tool-description');
                                    if (linkElement) {
                                        blocks.push({
                                            type: 'linkTool',
                                            data: {
                                                link: linkElement.getAttribute('href') || '',
                                                meta: {
                                                    title: linkElement.textContent,
                                                    description: linkDescription ? linkDescription.textContent : '',
                                                    image: linkImage ? { url: linkImage.getAttribute('src') } : {}
                                                }
                                            }
                                        });
                                        break;
                                    }
                                }
                                // If not a special div, treat as raw OR generic block if it has styles/classes (handled above)
                                blocks.push({
                                    type: 'raw',
                                    data: { html: element.outerHTML }
                                });
                                break;
                            default:
                                if (element.innerHTML.trim() !== '') {
                                    blocks.push({
                                        type: 'paragraph',
                                        data: { text: element.innerHTML }
                                    });
                                }
                        }
                    });
                }
            });

            return {
                time: Date.now(),
                blocks: blocks,
                version: '2.28.0'
            };
        }

        /**
         * Escape HTML special characters
         */
        escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    }

    // Create global instance only if it doesn't exist
    if (typeof window.editorJSManager === 'undefined') {
        window.editorJSManager = new EditorJSManager();
    }
}
