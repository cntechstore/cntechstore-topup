(function () {

    const STORAGE_KEY = "theme";

    function applyTheme(theme) {

        if (theme === "dark") {
            document.body.classList.add("dark");
        } else {
            document.body.classList.remove("dark");
        }

    }

    function getSavedTheme() {
        return localStorage.getItem(STORAGE_KEY) || "light";
    }

    function saveTheme(theme) {
        localStorage.setItem(STORAGE_KEY, theme);
    }

    document.addEventListener("DOMContentLoaded", function () {

        applyTheme(getSavedTheme());
        updateThemeIcon();

    });

    window.toggleTheme = function () {

        const isDark =
            document.body.classList.contains("dark");

        const newTheme =
            isDark ? "light" : "dark";

        applyTheme(newTheme);
        saveTheme(newTheme);

        updateThemeIcon();
    };

})();

function updateThemeIcon() {

    const btn = document.getElementById("themeBtn");

    if (!btn) return;

    if (document.body.classList.contains("dark")) {

        btn.innerHTML = '<i class="fa-solid fa-moon"></i>';

    } else {

        btn.innerHTML = '<i class="fa-solid fa-circle-half-stroke"></i>';

    }

}






function openMenu() {

    document.getElementById("navLinks")
        .classList.add("active");

    document.getElementById("overlay")
        .classList.add("active");

}


function closeMenu() {

    document.getElementById("navLinks")
        .classList.remove("active");

    document.getElementById("overlay")
        .classList.remove("active");

}

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

function scrollCategories(amount){

document.getElementById("categories")
    .scrollBy({
        left: amount,
        behavior: "smooth"
    });

}


function openCart(){

    document.getElementById("cartDrawer")
        .classList.add("active");

    document.getElementById("cartOverlay")
        .classList.add("active");

    fetch(BASE_URL + "/cart_ajax.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("cartBody").innerHTML = html;
        });
}

window.goCheckout = function () {

    console.log("goCheckout clicked");

    const title = document.getElementById("cartTitle");
    const body = document.getElementById("cartBody");

    if (title) title.innerText = "🧾 Checkout";

    fetch(BASE_URL + "/checkout.php")
        .then(res => res.text())
        .then(html => {

            if (body) {
                body.innerHTML = html;
            }

        })
        .catch(err => {
            console.error("Checkout error:", err);
        });

};


function closeCart(){

    document.getElementById("cartDrawer")
        .classList.remove("active");

    document.getElementById("cartOverlay")
        .classList.remove("active");
}

function updateQty(key, change){

    fetch("cart_update.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`key=${key}&change=${change}`
    })
    .then(res => res.text())
    .then(() => {
        openCart(); // reload cart
    });
}

function removeItem(key){

    fetch("cart_remove.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`key=${key}`
    })
    .then(res => res.text())
    .then(() => {
        openCart();
    });
}

function openSearch(){
    document.getElementById("searchPopup").classList.add("active");
    setTimeout(()=>{
        document.getElementById("searchInput").focus();
    },200);
}

function closeSearch(){
    document.getElementById("searchPopup").classList.remove("active");
    document.getElementById("searchResults").innerHTML = "";
}

function searchProducts(keyword) {

    const results = document.getElementById("searchResults");

    if(keyword.length < 1){

        results.innerHTML = "";

        return;

    }


    fetch(BASE_URL + 
        "search_ajax.php?keyword=" 
        + encodeURIComponent(keyword)
    )

    .then(res => res.json())

    .then(data => {


        let html = "";


        if(data.length === 0){

            html = `
            <div class="search-item">
                No result
            </div>
            `;

        }


        data.forEach(item => {


            html += `

            <a class="search-item"
               href="searchresults.php?id=${item.id}">


                <img 
                src="${item.image}"
                onerror="this.src='admin/uploads/no-image.png'"
                >


                <div>

                    <b>${item.name}</b>

                    <br>

                    <small>
                    K ${Number(item.price).toLocaleString()}
                    </small>

                </div>


            </a>

            `;


        });


        results.innerHTML = html;


    })

    .catch(err => {

        console.error(err);

    });

}



// =========================
// ADD TO CART (MAIN SYSTEM)
// =========================

document.addEventListener("click", function (e) {

    const btn = e.target.closest(".add-cart");
    if (!btn) return;

    const id = btn.dataset.id;
    if (!id) return;

    // ป้องกันกดซ้ำ
    if (btn.disabled) return;

    btn.disabled = true;
    const originalText = btn.innerHTML;

    // 1. loading state
    btn.innerHTML = "Adding...";

    fetch("add_cart.php?id=" + encodeURIComponent(id))
        .then(res => res.json())
        .then(data => {

            console.log(data);

            if (!data.success) {
                btn.innerHTML = "❌ Error";
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 800);
                return;
            }

            // 2. update cart badge
            const badge = document.querySelector(".cart-count");
            if (badge) {
                badge.textContent = data.count;
            }

            // 3. open cart drawer
            openCart();

            // 4. success state
            btn.innerHTML = "✓ Added";

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-cart-shopping"></i> Add to Cart';
            }, 1800);

        })
        .catch(err => {

            console.error(err);

            btn.innerHTML = "❌ Fail";

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 1000);

        });

});

window.goPayment = function () {

    console.log("GO PAYMENT CLICKED");

    const form = document.querySelector(".checkout-form");

    if (!form) {
        alert("Form not found");
        return;
    }

    const data = new FormData(form);

    fetch("checkout_ajax.php", {
        method: "POST",
        body: data
    })
    .then(r => r.json())
    .then(res => {

        console.log("RESPONSE:", res);

        if (res.status === "success") {

            window.location.href = res.redirect;

        } else {

            alert(res.message || "Error");

        }

    })
    .catch(err => {

        console.error("ERROR:", err);
        alert("Payment load failed");

    });

};

document.addEventListener("DOMContentLoaded", function(){

    const btn = document.getElementById("payBtn");

    if(!btn) return;

    btn.addEventListener("click", validateCheckout);

});

function validateCheckout(e){

    if(e) e.preventDefault();

    const fullname = document.getElementById("fullname");
    const email    = document.getElementById("email");
    const address  = document.getElementById("address");

    const fullnameError = document.getElementById("fullname-error");
    const emailError    = document.getElementById("email-error");
    const addressError  = document.getElementById("address-error");

    let valid = true;

    // reset
    [fullname,email,address].forEach(el=>{
        el.classList.remove("error");
    });

    fullnameError.innerText = "";
    emailError.innerText = "";
    addressError.innerText = "";

    if(!fullname.value.trim()){
        fullname.classList.add("error");
        fullnameError.innerText = "กรุณากรอกชื่อ";
        valid = false;
    }

    if(!email.value.trim()){
        email.classList.add("error");
        emailError.innerText = "กรุณากรอกอีเมล";
        valid = false;
    }

    if(!address.value.trim()){
        address.classList.add("error");
        addressError.innerText = "กรุณากรอกที่อยู่";
        valid = false;
    }

    if(!valid) return;

    const form = document.querySelector(".checkout-form");
    const data = new FormData(form);

    fetch("payment_ajax.php", {
        method: "POST",
        body: data
    })
    .then(r => r.json())
    .then(res => {

        if(res.status === "success"){
            window.location.href =
                "payment.php?order_id=" + res.order_id;
        } else {
            alert(res.message || "error");
        }

    })
    .catch(err=>{
        console.error(err);
        alert("Network error");
    });
};

function openBank(bank){

    const body =
        document.getElementById("cartBody");

    body.innerHTML = `
        <div class="loading">
            🏦 Loading ${bank}...
        </div>
    `;

    fetch(
        "bank-page.php?bank=" +
        encodeURIComponent(bank)
    )

    .then(res => res.json()) // ✅ FIX ตรงนี้

    .then(data => {

        console.log(data);

        if(data.success){

            // 👉 ไป API จริง / sandbox
            window.location.href = data.redirect;

        } else {

            body.innerHTML = `
                <div class="error-box">
                    <h3>❌ ${bank}</h3>
                    <p>${data.message || "Error"}</p>
                </div>
            `;

        }

    })

    .catch(err => {

        body.innerHTML = `
            <div class="error-box">
                <h3>❌ ${bank}</h3>
                <p>Connection failed</p>
            </div>
        `;

        console.error(err);

    });

}

// =========================
// CARD
// =========================

// =========================
// CARD PAYMENT (STRIPE)
// =========================

function openCard(card){

    const formData = {
        card: card,
        cart: JSON.parse(localStorage.getItem("cart") || "[]")
    };

    fetch("create_order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
    .then(r => r.json())
    .then(data => {

        if(!data.success){
            alert(data.message || "Order error");
            return;
        }

        // 👉 ไป stripe checkout ทันที
        window.location.href =
            "stripe_checkout.php?order_id=" + data.order_id;

    })
    .catch(err => {
        console.error(err);
        alert("Connection failed");
    });

}