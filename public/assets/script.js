class API {
    constructor() {
        this.api = config.url + "/index.php?c=sharepic";
        this.ai = config.url + "/index.php?c=openai";
    }
    delete(saving) {
        const payload = {
            saving: saving
        };
        fetch(this.api + "&m=delete", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        }).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {
            logger.log("deleted sharepic " + saving);
        }).catch(error => console.error("Error:", error));
    }
    load(path = "templates/mint/start.html") {
        const data = {
            template: path
        };
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "text/html"
            },
            body: JSON.stringify(data)
        };
        fetch(this.api + "&m=load", options).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {
            document.getElementById("canvas").innerHTML = data;
            document.querySelectorAll(".server-only").forEach(element => {
                element.remove();
            });
            cockpit.setup_sharepic();
            rte.init();
            sg.init();
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, "text/html");
            const script = doc.querySelector("script").innerText;
            eval(script);
            logger.prepare_log_data({});
            logger.log("loads template " + path);
        }).catch(error => console.error("Error:", error));
    }
    prepare() {
        component.unselect();
        const canvas = document.getElementById("canvas");
        const clonedCanvas = canvas.cloneNode(true);
        clonedCanvas.querySelector("#greentextContextMenu")?.remove();
        clonedCanvas.insertAdjacentHTML("afterbegin", '<link rel="stylesheet" href="assets/styles.css?r=1">\n');
        clonedCanvas.insertAdjacentHTML("afterbegin", '<base href="../../../html/">\n');
        const bgImage = document.getElementById("sharepic").style.backgroundImage;
        if (bgImage !== "") {
            const url = new URL(bgImage, "http://dummybase.com");
            const params = new URLSearchParams(url.search);
            const backgroundImage = params.get("p").replace(/"\)$/g, "");
            clonedCanvas.querySelector("#sharepic").style.backgroundImage = `url(../${backgroundImage})`;
        }
        const data = {
            data: clonedCanvas.innerHTML,
            size: {
                width: document.getElementById("width").value,
                height: document.getElementById("height").value,
                zoom: document.getElementById("sharepic").dataset.zoom
            },
            body_class: document.getElementsByTagName("body")[0].classList.value
        };
        return data;
    }
    save(mode = "save") {
        const name = prompt("Name des Sharepics", "Sharepic");
        const data = this.prepare();
        data.name = name;
        data.mode = mode;
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        };
        fetch(this.api + "&m=save", options).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {
            data = JSON.parse(data);
            try {
                const html = `<div class="dropdown-item-double">
            <button class="did-1" onclick="api.load('${data.full_path}')">
              ${name}
            </button>
            <button class="did-2" onclick="ui.deleteSavedSharepic(this, '${data.id}')">
              <img src="assets/icons/delete.svg">
            </button>
          </div>`;
                document.getElementById("my-sharepics").insertAdjacentHTML("beforeend", html);
            } catch (e) {
                console.error(e);
            }
            logger.log("saved sharepic " + name);
        }).catch(error => console.error("Error:", error));
    }
    create() {
        document.querySelector(".create").disabled = true;
        document.querySelector(".create").classList.add("waiting");
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(this.prepare())
        };
        this.showWaiting();
        fetch(this.api + "&m=create", options).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {
            data = JSON.parse(data);
            const a = document.createElement("a");
            a.href = config.url + "/" + data.path;
            a.download = "sharepic.png";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            document.querySelector(".create").disabled = false;
            document.querySelector(".create").classList.remove("waiting");
            this.closeWaiting();
            logger.log("created sharepic");
        }).catch(error => console.error("Error:", error));
    }
    dalle() {
        const data = {
            prompt: document.getElementById("dalle_prompt").value
        };
        if (data.prompt === "") {
            alert(lang["Enter prompt for image"]);
            return;
        }
        document.getElementById("dalle_result").style.display = "block";
        document.getElementById("dalle_result_waiting").style.display = "block";
        document.getElementById("dalle_result_response").style.display = "none";
        const createButton = document.querySelector('[onClick="api.dalle()"');
        const createButtonLabel = createButton.innerHTML;
        createButton.innerHTML = "...";
        createButton.disabled = true;
        const startGeneration = Math.floor(Date.now() / 1e3);
        document.getElementById("dalle_result_waiting_progress").innerHTML = 0;
        const dalleWaiting = window.setInterval(function() {
            const seconds = Math.floor(Date.now() / 1e3) - startGeneration;
            document.getElementById("dalle_result_waiting_progress").innerHTML = seconds;
        }, 1e3);
        logger.log("used dalle with prompt: " + data.prompt);
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        };
        fetch(this.ai + "&m=dalle", options).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {
            data = JSON.parse(data);
            const hint = data.data[0].revised_prompt;
            const url = data.local_file;
            document.getElementById("dalle_result_waiting").style.display = "none";
            document.getElementById("dalle_result_response").style.display = "block";
            document.getElementById("dalle_prompt").value = hint;
            document.getElementById("dalle_result_image").innerHTML = '<img src="' + url + '" />';
            const copyright = document.querySelector("#sharepic [id^=copyright_]");
            if (!copyright) {
                component.add("copyright");
            }
            document.querySelector("#sharepic [id^=copyright_]").innerHTML = "Bild generiert von DALL-E";
            ui.showTab("search");
            config.dalle = {
                url: url
            };
            const endGeneration = Math.floor(Date.now() / 1e3);
            logger.log("waited " + (endGeneration - startGeneration) + " seconds for dalle result");
            createButton.innerHTML = createButtonLabel;
            createButton.disabled = false;
            clearInterval(dalleWaiting);
        }).catch(error => console.error("Error:", error));
    }
    useDalle() {
        document.getElementById("sharepic").style.backgroundImage = `url('${config.dalle.url}')`;
        logger.prepare_log_data({
            imagesrc: "dalle"
        });
    }
    upload(btn) {
        if (!btn.files.length) {
            return;
        }
        const file = btn.files[0];
        const formData = new FormData();
        formData.append("file", file);
        const imageUrl = URL.createObjectURL(file);
        document.getElementById("sharepic").style.backgroundImage = `url('${imageUrl}')`;
        document.getElementById("sharepic").style.filter = "grayscale(100%)";
        const xhr = new XMLHttpRequest();
        xhr.open("POST", this.api + "&m=upload", true);
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round(e.loaded / e.total * 100);
                let message = lang["Uploading image"] + " " + percentComplete + "%";
                if (percentComplete > 98) {
                    message = lang["Processing image"];
                }
                document.querySelector(".workbench-below .message").innerHTML = message;
            }
        };
        xhr.onload = function() {
            if (this.status === 200) {
                const resp = JSON.parse(this.response);
                document.getElementById("sharepic").style.backgroundImage = `url('${resp.path}')`;
                logger.prepare_log_data({
                    imagesrc: "upload"
                });
            } else {
                console.error("Error:", this.status, this.statusText);
            }
            document.getElementById("sharepic").style.filter = "none";
            document.querySelector(".workbench-below .message").innerHTML = "";
            const copyright = document.querySelector("#sharepic [id^=copyright_]");
            if (copyright) {
                copyright.innerHTML = "";
            }
            ui.showTab("background");
            logger.log("uploaded file");
        };
        xhr.onerror = function() {
            console.error("Error:", this.status, this.statusText);
        };
        xhr.send(formData);
    }
    uploadAddPic(btn) {
        if (!btn.files.length || cockpit.target === null) {
            return;
        }
        const file = btn.files[0];
        const formData = new FormData();
        formData.append("file", file);
        const imageUrl = URL.createObjectURL(file);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", this.api + "&m=upload_addpic", true);
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round(e.loaded / e.total * 100);
            }
        };
        xhr.onload = function() {
            if (this.status === 200) {
                const resp = JSON.parse(this.response);
                cockpit.target.querySelector(".ap_image").style.backgroundImage = `url('${resp.path}')`;
                logger.prepare_log_data({
                    imagesrc: "addpic"
                });
            } else {
                console.error("Error:", this.status, this.statusText);
            }
            logger.log("uploaded addpic");
        };
        xhr.onerror = function() {
            console.error("Error:", this.status, this.statusText);
        };
        xhr.send(formData);
    }
    loadByUrl(url) {
        const data = {
            url: url
        };
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        };
        fetch(this.api + "&m=load_from_url", options).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {
            data = JSON.parse(data);
            document.getElementById("sharepic").style.backgroundImage = `url('${data.path}')`;
        }).catch(error => console.error("Error:", error));
    }
    showWaiting() {
        document.getElementsByTagName("body")[0].style.opacity = .3;
        document.getElementById("waiting").showModal();
    }
    closeWaiting() {
        document.getElementsByTagName("body")[0].style.opacity = 1;
        document.getElementById("waiting").close();
    }
}

class Cockpit {
    target = null;
    setup_sharepic() {
        document.getElementById("width").value = document.getElementById("sharepic").dataset.width;
        document.getElementById("height").value = document.getElementById("sharepic").dataset.height;
        const backgroundSize = document.getElementById("sharepic").style.backgroundSize;
        document.getElementById("background_size").value = backgroundSize === "cover" ? 100 : backgroundSize.replace("%", "");
        document.getElementById("background_color").value = ui.rgbToHex(document.getElementById("sharepic").style.backgroundColor);
    }
    setup_greentext(element) {}
    setup_background(element) {
        const slider = document.getElementById("copyright_size");
        slider.value = element.style.fontSize.replace("px", "");
        document.getElementById("copyright_color").value = ui.rgbToHex(element.style.color);
        cockpit.target = document.getElementById("copyright");
    }
    setup_freetext(element) {
        document.getElementById("text_size").value = element.style.fontSize.replace("px", "");
    }
    setup_eyecatcher(element) {
        if (config.starttemplate === "de") {
            return;
        }
        document.getElementById("eyecatcher_size").value = element.style.fontSize.replace("px", "");
        document.getElementById("eyecatcher_color").value = ui.rgbToHex(element.style.color);
        document.getElementById("eyecatcher_bgcolor").value = ui.rgbToHex(element.querySelector("#sticker_bg").style.fill);
        document.getElementById("eyecatcher_rotation").value = element.style.transform.replace("rotate(", "").replace("deg)", "");
    }
    setup_logo(element) {
        document.getElementById("logo_size").value = element.style.width.replace("px", "");
        const file = document.getElementById("logo_file");
        let url = element.style.backgroundImage.replace(/url\("(\.\.\/)*/, "").replace('")', "");
        if (!url.startsWith("/")) {
            url = "/" + url;
        }
        file.value = url;
    }
    setup_addpicture(element) {
        document.getElementById("addpic_color").value = ui.rgbToHex(element.querySelector(".ap_text").style.color);
        document.getElementById("addpicture_size").value = element.querySelector(".ap_image").style.width.replace("px", "");
    }
    setup_copyright(element) {
        document.getElementById("copyright_size").value = element.style.fontSize.replace("px", "");
        document.getElementById("copyright_color").value = ui.rgbToHex(element.style.color);
        document.getElementById("add_copyright").style.display = "none";
    }
}

class Component {
    add(item) {
        const pattern = document.querySelector(`[data-id=${item}]`);
        const max = pattern?.dataset?.max || -1;
        const currentCount = document.querySelectorAll(`#sharepic [id^=${item}_]`).length;
        if (max !== -1 && currentCount >= max) {
            alert(lang["Max reached"]);
            return;
        }
        const clonedElement = pattern.cloneNode(true);
        const newId = pattern.dataset.id + "_" + Math.round(Math.random() * 100);
        clonedElement.setAttribute("id", newId);
        clonedElement.removeAttribute("data-id");
        clonedElement.setAttribute("class", pattern.dataset.class);
        clonedElement.removeAttribute("data-class");
        document.getElementById("sharepic").insertAdjacentHTML("beforeend", clonedElement.outerHTML);
        const newElement = document.getElementById(newId);
        cockpit.target = newElement;
        const inputEvent = new Event("input");
        newElement.dispatchEvent(inputEvent);
        component.select(newElement);
        this.toFront(newElement);
        undo.commit();
    }
    select(element) {
        if (element.classList.contains("selected")) {
            return;
        }
        document.querySelector(".selected")?.classList.remove("selected");
        element.classList.add("selected");
        ui.showTab(element.dataset.cockpit, element);
    }
    unselect() {
        document.querySelectorAll(".selected_only").forEach(element => {
            element.style.display = "none";
        });
        document.querySelector(".selected")?.classList.remove("selected");
        cockpit.target = null;
        rte.hide();
    }
    delete() {
        document.getElementById("add_copyright").style.display = "flex";
        cockpit.target.remove();
        this.unselect();
    }
    toFront(element) {
        if (cockpit.target === null) {
            return;
        }
        const highestZIndex = [ ...document.querySelectorAll(".draggable") ].reduce((maxZIndex, element) => {
            const zIndex = parseInt(getComputedStyle(element).zIndex, 10);
            return isNaN(zIndex) ? maxZIndex : Math.max(maxZIndex, zIndex);
        }, 0);
        cockpit.target.style.zIndex = (highestZIndex + 1).toString();
        undo.commit();
    }
    toBack(element) {
        if (cockpit.target === null) {
            return;
        }
        const allElements = [ ...document.querySelectorAll("#sharepic > *") ];
        const elementsWithZIndex = allElements.filter(element => {
            const zIndex = parseInt(getComputedStyle(element).zIndex, 10);
            return !isNaN(zIndex) && zIndex !== 0;
        });
        const sortedElementsByZIndex = elementsWithZIndex.sort((a, b) => {
            const zIndexA = parseInt(getComputedStyle(a).zIndex, 10);
            const zIndexB = parseInt(getComputedStyle(b).zIndex, 10);
            return zIndexA - zIndexB;
        });
        let thisZIndex = 1;
        sortedElementsByZIndex.forEach(element => {
            thisZIndex++;
            element.style.zIndex = thisZIndex.toString();
        });
        cockpit.target.style.zIndex = 1;
        undo.commit();
    }
    startDrag(event) {
        if (event.button !== 0 || !event.target.classList.contains("draggable")) {
            return;
        }
        cockpit.target = this.parentWithOnMouseDown(event.target) || console.error("No parent with onmousedown found");
        component.dragInfo = {
            xOffset: event.clientX - cockpit.target.getBoundingClientRect().left + document.getElementById("canvas").getBoundingClientRect().left,
            yOffset: event.clientY - cockpit.target.getBoundingClientRect().top + document.getElementById("canvas").getBoundingClientRect().top
        };
        document.addEventListener("mousemove", component.dragging);
        document.addEventListener("mouseup", component.stopDrag);
    }
    dragging(e) {
        e.preventDefault();
        let x = e.clientX - component.dragInfo.xOffset;
        let y = e.clientY - component.dragInfo.yOffset;
        if (cockpit.target.dataset.dragconstraint === "true") {
            const maxLeft = document.getElementById("canvas").offsetWidth - cockpit.target.offsetWidth;
            const maxTop = document.getElementById("canvas").offsetHeight - cockpit.target.offsetHeight;
            x = Math.min(Math.max(x, 0), maxLeft);
            y = Math.min(Math.max(y, 0), maxTop);
        }
        cockpit.target.style.top = `${y}px`;
        cockpit.target.style.left = `${x}px`;
    }
    stopDrag() {
        document.removeEventListener("mousemove", component.dragging);
        document.removeEventListener("mouseup", component.stopDrag);
        sg.putBackOnCanvas();
        undo.commit();
    }
    parentWithOnMouseDown(element) {
        while (element) {
            if (element.onmousedown) {
                return element;
            }
            element = element.parentElement;
        }
        return null;
    }
}

class Logger {
    constructor() {
        this.log_data = {};
    }
    prepare_log_data(data) {
        this.log_data = data;
    }
    log(data) {
        const payload = {
            data: data,
            ...this.log_data
        };
        fetch(config.url + "/index.php?c=felogger&m=normal", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        }).then(response => {
            if (response.status !== 200) {
                throw new Error(response.status + " " + response.statusText);
            }
            return response.text();
        }).then(data => {}).catch(error => console.error("Error:", error));
    }
}

class Pixabay {
    constructor() {
        document.getElementById("pixabay_q").addEventListener("keydown", event => {
            if (event.key === "Enter") {
                this.search();
            }
        });
    }
    search() {
        const q = document.getElementById("pixabay_q").value;
        const page = 1;
        const perPage = 80;
        const url = `https://pixabay.com/api/?key=${config.pixabay.apikey}&q=${encodeURIComponent(q)}&image_type=photo&page=${page}&per_page=${perPage}&lang=de`;
        fetch(url).then(response => response.json()).then(data => {
            logger.log("searches for " + q + " and gets " + data.hits.length + " results");
            this.showResults(data);
        }).catch(error => console.error("Error:", error));
    }
    showResults(data) {
        const page = document.getElementById("pixabay_page");
        page.classList.add("show");
        const results = document.getElementById("pixabay_results");
        results.classList.add("show");
        results.innerHTML = "";
        if (data.hits === undefined || data.hits.length === 0) {
            const q = document.getElementById("pixabay_q").value;
            results.innerHTML = `<div class="no_results">Für den Suchbegriff "${q}" wurden keine Bilder gefunden.</div>`;
            return;
        }
        data.hits.forEach(hit => {
            const img = document.createElement("div");
            img.style.backgroundImage = `url('${hit.previewURL}')`;
            img.classList.add("image");
            img.setAttribute("data-url", hit.webformatURL);
            img.setAttribute("data-user", hit.user);
            img.setAttribute("data-pageurl", hit.pageURL);
            img.onclick = () => {
                const q = document.getElementById("pixabay_q").value;
                logger.prepare_log_data({
                    imagesrc: "pixabay",
                    q: q
                });
                logger.log("clicks on image after search for " + q);
                document.getElementById("sharepic").style.backgroundImage = img.style.backgroundImage;
                api.loadByUrl(img.dataset.url);
                const copyright = document.querySelector("#sharepic [id^=copyright_]");
                if (!copyright) {
                    component.add("copyright");
                }
                document.querySelector("#sharepic [id^=copyright_]").innerHTML = `Bild von ${img.dataset.user} auf pixabay.com`;
            };
            results.appendChild(img);
        });
    }
}

class RichTextEditor {
    init() {}
    add(selector) {
        if (!document.querySelector(selector)) {
            return;
        }
        this.setPosition();
        undo.commit();
    }
    setPosition() {
        const text = cockpit.target.getBoundingClientRect();
        const rte = document.querySelector("#rte");
        const toolbarHeight = parseInt(getComputedStyle(document.querySelector("#rte")).height.replace("px", ""));
        rte.style.top = text.top - toolbarHeight - 15 + "px";
        rte.style.left = text.left + "px";
    }
    hide() {
        const rte = document.querySelector("#rte");
        rte.style.top = "-100px";
    }
    setStyle(styleName, value) {
        const selection = window.getSelection();
        if (!selection.rangeCount) {
            return;
        }
        const range = selection.getRangeAt(0);
        const clonedSelection = range.cloneContents();
        let styleChanged = false;
        clonedSelection.childNodes.forEach(function(node) {
            if (node.nodeType === Node.ELEMENT_NODE && node.style[styleName]) {
                node.style[styleName] = value;
                styleChanged = true;
            }
        });
        if (styleChanged) {
            range.deleteContents();
            range.insertNode(clonedSelection);
            return;
        }
        const tag = document.createElement("span");
        tag.appendChild(clonedSelection);
        tag.style[styleName] = value;
        range.deleteContents();
        range.insertNode(tag);
    }
    clearFormat() {
        const selection = window.getSelection();
        if (!selection.rangeCount) {
            return;
        }
        const range = selection.getRangeAt(0);
        const textNode = document.createTextNode(range.toString());
        range.deleteContents();
        range.insertNode(textNode);
    }
    showSource() {
        const container = document.querySelector("#freetext>div");
        const sourceContainer = document.querySelector("#source");
        sourceContainer.innerHTML = container.innerHTML.replace(/</g, "&lt;");
        sourceContainer.style.display = "block";
        document.querySelector("#show_source").style.display = "none";
        document.querySelector("#show_rte").style.display = "flex";
    }
    showRTE() {
        const source = document.querySelector("#source").value;
        document.querySelector("#freetext>div").innerHTML = source.replace(/&lt;/g, "<");
        document.querySelector("#source").style.display = "none";
        document.querySelector("#show_source").style.display = "flex";
        document.querySelector("#show_rte").style.display = "none";
    }
}

class Sharepic {
    constructor() {
        document.querySelectorAll("[data-sizepreset]").forEach(element => {
            element.addEventListener("click", function() {
                const sizePreset = this.dataset.sizepreset.split(":");
                document.getElementById("width").value = sizePreset[0];
                document.getElementById("height").value = sizePreset[1];
                const event = new Event("change");
                document.getElementById("height").dispatchEvent(event);
            });
        });
        document.getElementById("background_size").addEventListener("input", () => {
            const percentage = document.getElementById("background_size").value;
            this.backgroundZoom(percentage);
        });
        this.startDrag();
    }
    init() {
        const sg = document.getElementById("sharepic");
        sg.addEventListener("mouseover", () => {
            this.draggable = true;
        });
        sg.addEventListener("mouseout", () => {
            this.draggable = false;
        });
        this.setSize();
        this.startDrag();
    }
    backgroundColor(btn) {
        document.getElementById("sharepic").style.backgroundColor = btn.value;
    }
    backgroundZoom(percentage) {
        const sg = document.getElementById("sharepic");
        const backgroundSize = sg.style.backgroundSize;
        if (backgroundSize === "" || backgroundSize === "cover") {
            sg.style.backgroundSize = "100%";
            sg.style.backgroundPositionX = "0px";
            sg.style.backgroundPositionY = "0px";
            sg.style.backgroundRepeat = "no-repeat";
            sg.style.backgroundColor = "white";
            this.startDrag();
        }
        sg.style.backgroundSize = percentage + "%";
    }
    startDrag() {
        const sg = document.getElementById("sharepic");
        const moveHandler = event => this.drag(event);
        sg.addEventListener("mousedown", event => {
            if (event.button !== 0) {
                return;
            }
            if (!document.getElementById("drag_background").checked) {
                return;
            }
            const sg = document.getElementById("sharepic");
            this.startMouseX = event.clientX;
            this.startMouseY = event.clientY;
            this.startBackgroundX = parseInt(sg.style.backgroundPositionX.replace("px", ""), 10);
            this.startBackgroundY = parseInt(sg.style.backgroundPositionY.replace("px", ""), 10);
            sg.addEventListener("mousemove", moveHandler);
        });
        sg.addEventListener("mouseup", event => {
            sg.removeEventListener("mousemove", moveHandler);
        });
    }
    drag(event) {
        const sg = document.getElementById("sharepic");
        const dx = event.clientX - this.startMouseX + this.startBackgroundX;
        const dy = event.clientY - this.startMouseY + this.startBackgroundY;
        sg.style.backgroundPositionX = dx + "px";
        sg.style.backgroundPositionY = dy + "px";
    }
    resetBackground() {
        const sg = document.getElementById("sharepic");
        sg.style.backgroundSize = "cover";
        sg.style.backgroundRepeat = "no-repeat";
        sg.style.backgroundPositionX = "0px";
        sg.style.backgroundPositionY = "0px";
    }
    deleteBackgroundImage() {
        const sg = document.getElementById("sharepic");
        sg.style.backgroundImage = "none";
    }
    setSize() {
        const sg = document.getElementById("sharepic");
        const width = parseInt(document.getElementById("width").value);
        const height = parseInt(document.getElementById("height").value);
        const ratio = width / height;
        const maxWidth = 800;
        const maxHeight = 600;
        const zoom = Math.min(maxWidth / width, maxHeight / height);
        const newWidth = width * zoom;
        const newHeight = height * zoom;
        sg.style.width = newWidth + "px";
        sg.style.height = newHeight + "px";
        sg.dataset.zoom = zoom;
        sg.dataset.width = width;
        sg.dataset.height = height;
        this.putBackOnCanvas();
        document.getElementById("sharepic").classList.toggle("small", newWidth < 400);
    }
    putBackOnCanvas() {
        document.querySelectorAll("#sharepic .draggable").forEach(element => {
            const sharepicWidth = parseInt(document.getElementById("sharepic").style.width.replace("px", ""));
            const sharepicHeight = parseInt(document.getElementById("sharepic").style.height.replace("px", ""));
            if (element.style.left.replace("px", "") > sharepicWidth) {
                element.style.left = sharepicWidth - element.offsetWidth + "px";
            }
            if (element.style.top.replace("px", "") > sharepicHeight) {
                element.style.top = sharepicHeight - element.offsetHeight + "px";
            }
            if (element.style.left.replace("px", "") < -element.offsetWidth) {
                element.style.left = "0px";
            }
            if (element.style.top.replace("px", "") < -element.offsetHeight) {
                element.style.top = "0px";
            }
        });
    }
}

const mouseDownEvent = new MouseEvent("mousedown", {
    bubbles: true,
    cancelable: true,
    button: 0
});

class UI {
    showTab(tab, element = null) {
        if (element) {
            cockpit.target = element;
            const cockpitEelement = "setup_" + element.dataset.cockpit;
            if (typeof cockpit[cockpitEelement] === "function") {
                cockpit[cockpitEelement](element);
            }
        } else {
            component.unselect();
            const firstElement = document.querySelector(`#sharepic > [data-cockpit="${tab}"]`);
            if (firstElement !== null) {
                document.querySelector(".selected")?.classList.remove("selected");
                firstElement.classList.add("selected");
                this.showTab(tab, firstElement);
                return;
            }
        }
        document.querySelectorAll(".selected_only").forEach(element => {
            element.style.display = cockpit.target === null ? "none" : "block";
        });
        document.querySelector("#cockpit .show")?.classList.remove("show");
        document.querySelector("#cockpit .active")?.classList.remove("active");
        document.getElementById("cockpit_" + tab)?.classList.add("show");
        document.getElementById("tab_btn_" + tab)?.classList.add("active");
        document.getElementById("drag_background").checked = false;
    }
    setLang(language) {
        if (confirm(lang["All changes lost"]) === false) {
            return false;
        }
        document.cookie = "lang=" + language + "; path=/";
        window.document.location.reload();
    }
    reload() {
        if (confirm(lang["All changes lost"]) === false) {
            return false;
        }
        window.document.location.reload();
    }
    deleteSavedSharepic(origin, target) {
        if (!window.confirm(lang["Are you sure?"])) {
            return false;
        }
        api.delete(target);
        origin.parentElement.remove();
    }
    close(target) {
        document.querySelector(target).classList.remove("show");
    }
    rgbToHex(rgb) {
        const sep = rgb.indexOf(",") > -1 ? "," : " ";
        rgb = rgb.substr(4).split(")")[0].split(sep);
        let r = (+rgb[0]).toString(16);
        let g = (+rgb[1]).toString(16);
        let b = (+rgb[2]).toString(16);
        if (r.length === 1) {
            r = "0" + r;
        }
        if (g.length === 1) {
            g = "0" + g;
        }
        if (b.length === 1) {
            b = "0" + b;
        }
        return "#" + r + g + b;
    }
}

class Undo {
    constructor() {
        document.addEventListener("keydown", function(event) {
            if (event.ctrlKey && event.key === "z") {
                this.undo();
            }
        }.bind(this));
        localStorage.setItem("commits", JSON.stringify([]));
        this.undoing = false;
        document.querySelectorAll("input").forEach(element => {
            element.addEventListener("blur", () => {
                this.commit();
            });
        });
    }
    commit() {
        const data = document.getElementById("canvas").innerHTML;
        const commits = JSON.parse(localStorage.getItem("commits"));
        commits.push(data);
        localStorage.setItem("commits", JSON.stringify(commits));
        this.undoing = false;
    }
    undo() {
        const commits = JSON.parse(localStorage.getItem("commits"));
        if (commits.length <= 1) {
            alert("Nothing to undo");
            return;
        }
        if (!this.undoing) {
            this.undoing = true;
            commits.pop();
        }
        const latestCommit = commits.pop();
        localStorage.setItem("commits", JSON.stringify(commits));
        document.getElementById("canvas").innerHTML = latestCommit;
        cockpit.setup_sharepic();
    }
}

var api, sg, cockpit, undo, pixabay, component, rte, logger, ui;

window.onload = function() {
    api = new API();
    api.load("templates/" + config.starttemplate + "/start.html");
    sg = new Sharepic();
    cockpit = new Cockpit();
    undo = new Undo();
    pixabay = new Pixabay();
    component = new Component();
    ui = new UI();
    rte = new RichTextEditor();
    logger = new Logger();
    ui.showTab("download");
};