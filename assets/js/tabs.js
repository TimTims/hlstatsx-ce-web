function Tabs(container, togglers, options) {
    this.container = document.getElementById(container);
    this.togglers = Array.isArray(togglers) ? togglers : [togglers];
    this.options = Object.assign({
        defaultTab: false,
        loadingImage: false,
        game: null,
        mode: null,
        extra: [],
    }, options);

    this.currentRequest = false;
    this.elements = [];
    this.currentTab = null;

    this.initialize();
}

Tabs.prototype = {
    initialize: function () {
        this.togglers.forEach(this.addTab.bind(this));

        if (this.options.defaultTab) {
            const defaultTab = this.options.defaultTab.replace(/^tab_/, '');
            const defaultToggler = this.togglers.find(toggler => toggler.id === defaultTab);
            if (defaultToggler) {
                this.loadTab(defaultToggler, this.togglers.indexOf(defaultToggler));
            }
        }
    },

    addTab: function (toggler) {
        this.togglers.push(toggler);

        if (typeof toggler === 'string') {
            toggler = document.getElementById(toggler);
        }

        toggler.addEventListener('click', this.loadTab.bind(this, toggler, this.togglers.indexOf(toggler)));

        const tab = toggler.id.replace(/^tab_/, '');
        if (tab === this.options.defaultTab) {
            this.loadTab(toggler, this.togglers.indexOf(toggler));
        }
    },

    updateTab: function (txt) {
        if (this.loading) {
            this.loading.remove();
        }
        this.elements[this.currentRequest.currentTab] = document.createElement('div');
        this.elements[this.currentRequest.currentTab].innerHTML = txt;
        this.container.appendChild(this.elements[this.currentRequest.currentTab]);

        // Evaluate the response AFTER it's been created
        txt.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
        this.currentRequest = false;
    },

    refreshTab: function (change) {
        this.options.extra = Object.assign({}, this.options.extra, change);

        for (let i = 0; i < this.togglers.length; i++) {
            if (this.togglers[i].id.replace(/^tab_/, '') === this.currentTab) {
                this.elements[i].remove();
                this.loadTab(this.togglers[i], i);
                return;
            }
        }
    },

    loadTab: function (toggler, idx) {
		const tab = toggler.id.replace(/^tab_/, '');
	
		if (this.currentTab === tab && this.container.contains(this.elements[idx])) {
			return;
		}
	
		for (let i = 0; i < this.togglers.length; i++) {
			if (this.togglers[i] === toggler) {
				this.togglers[i].classList.add('active');
			} else if (typeof this.togglers[i].classList !== 'undefined') {
				this.togglers[i].classList.remove('active');
			}
		}
	
		this.currentTab = tab;
	
		if (this.currentRequest) {
			if (this.loading) {
				this.loading.remove();
			}
			this.currentRequest.abort();
		}
	
		Array.from(this.container.children).forEach(function (el) {
			el.style.display = 'none';
		});
	
		if (this.container.contains(this.elements[idx])) {
			this.elements[idx].style.display = 'block';
			return;
		}
	
		this.loading = document.createElement('div');
		this.loading.innerHTML = '<br /><br /><center><b>Loading...</b><br /><img src="' + this.options.loadingImage + '" alt="Loading..." /></center>';
		this.container.appendChild(this.loading);
	
		const url = `hlstats.php?mode=${this.options.mode}&type=ajax&game=${this.options.game}&tab=${tab}&${this.serializeOptions(this.options.extra)}`;
	
		const xhr = new XMLHttpRequest();
		xhr.open('GET', url, true);
	
		xhr.onload = (function () {
			if (xhr.status === 200) {
				this.updateTab(xhr.responseText);
			}
		}).bind(this); // Bind the function to the correct context
	
		xhr.send();
		this.currentRequest = xhr;
	},

    serializeOptions: function (options) {
        return Object.keys(options).map(function (key) {
            return encodeURIComponent(key) + '=' + encodeURIComponent(options[key]);
        }).join('&');
    },
};