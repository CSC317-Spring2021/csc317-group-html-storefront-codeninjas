// This script loads and displays product data on product{ID}.html pages.
// The product page's HTML filename must include the product ID (can be any string) - product123.html will load the product data of the product with ID 123, and productfoo.html will load the product data of product ID foo.
(async () => {
    try {
        /**
         * Fetch and find specific product data.
         */
        const pageProductId = window.location.pathname.split("/").slice(-1).pop().replace(/\D/g, "");
        if (typeof pageProductId !== "string") {
            throw Error("Product ID not specified.");
        };
        const response = await fetch("/mock-data/products.json"); // This will be replaced with an actual API call.
        if (response.status < 200 || response.status >= 300) {
            throw Error(response.statusText);
        };
        const products = await response.json();
        const currentProduct = products.find(product => {
            return product.id === pageProductId;
        });
        if (!currentProduct) {
            throw Error("Product ID not found.");
        };

        /**
         * Populate page with product fields.
         */
        document.title = `${currentProduct.name}`;

        const productImageElement = document.getElementById("product-listing-image");
        productImageElement.setAttribute("src", `../../images/${currentProduct.images[0]}`); // In line with the current HTML, this only displays the first image.

        const productNameElement = document.getElementById("product-listing-name");
        productNameElement.textContent = currentProduct.name;

        const productRatingElement = document.getElementById("product-listing-rating");
        productRatingElement.textContent = `Rating: ${generateStars(currentProduct.rating)}`;

        const productStockElement = document.getElementById("product-listing-stock");
        const LOW_STOCK_CUTOFF = 20;
        if (currentProduct.stock > LOW_STOCK_CUTOFF) {
            productStockElement.textContent = `In Stock.`;
        } else {
            productStockElement.textContent = `Only ${currentProduct.stock} left in stock - order soon.`;
            productStockElement.style.color = `#b12704`;
        };

        const productPriceElement = document.getElementById("product-listing-price");
        const originalPriceSpan = document.createElement("span");
        originalPriceSpan.appendChild(document.createTextNode(`$${currentProduct.price.usd}`));
        productPriceElement.appendChild(originalPriceSpan);
        // If there is a discounted price, strikethrough the original price and append the discounted price.
        if (currentProduct.discount_price.usd) {
            originalPriceSpan.style.setProperty("text-decoration", "line-through")
            const discountedPriceSpan = document.createElement("span");
            discountedPriceSpan.appendChild(document.createTextNode(`$${currentProduct.discount_price.usd}`));
            productPriceElement.appendChild(document.createTextNode("\u00A0"));
            productPriceElement.appendChild(discountedPriceSpan);
        };

        const productDescriptionListElement = document.getElementById("product-listing-description");
        for (const desc of currentProduct.description) {
            const li = document.createElement("li");
            li.appendChild(document.createTextNode(desc));
            productDescriptionListElement.appendChild(li);
        };
    } catch(e) {
        console.error(e);
    };
})();

/**
 * Generates a string of 5 stars, with ```filledStars``` stars filled in (solid) and the remainder hollow.
 * @param {Number} filledStars The number of filled stars to generate. Should be between 0 and 5.
 */
function generateStars(filledStars) {
    const MAX_STARS = 5;
    const emptyStarChar = '☆';
    const filledStarChar = '★';

    filledStars = Math.min(Math.max(0, Math.trunc(filledStars || 0)), MAX_STARS);
    return `${filledStarChar.repeat(filledStars)}${emptyStarChar.repeat(MAX_STARS - filledStars)}`;
};