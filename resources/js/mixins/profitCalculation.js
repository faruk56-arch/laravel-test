export default {
    methods: {
        buyerObvyTiers(profit, ratio) {
            if ((profit + 1) / ratio < 29.99) return 1;
            else if ((profit + 1.5) / ratio < 99.99) return 1.5;
            else if ((profit + 3.5) / ratio < 249.99) return 3.5;
            else if ((profit + 6.5) / ratio < 499.99) return 6.5;
            else if ((profit + 12.5) / ratio < 1199.99) return 12.5;
            else if ((profit + 15) / ratio < 2999.99) return 15;
            else if ((profit + 25) / ratio < 10000) return 25;
            else if ((profit + 50) / ratio < 200000) return 50;
            return 50;
        },
        sellerObvyTiers(price, ratio) {
            if (price * ratio - 1 < 29.99) return 1;
            else if (price * ratio - 1.5 < 99.99) return 1.5;
            else if (price * ratio - 3.5 < 249.99) return 3.5;
            else if (price * ratio - 6.5 < 499.99) return 6.5;
            else if (price * ratio - 12.5 < 1199.99) return 12.5;
            else if (price * ratio - 15 < 2999.99) return 15;
            else if (price * ratio - 25 < 10000) return 25;
            else if (price * ratio - 50 < 200000) return 50;
            return 50;
        },
        buyerPrice(regularProfit, ratio = 0.88) {
            if (!regularProfit) {
                return {price: '', commission: ''}
            }
            let profit = parseFloat(regularProfit);
            let obvy = this.buyerObvyTiers(profit, ratio);
            const price = ((profit + obvy) / ratio).toFixed(2)
            const commission = (price - profit).toFixed(2)
            return {price, commission}
        },
        sellerPrice(regularPrice, ratio = 0.88) {
            if (!regularPrice) {
                return {price: '', commission: ''}
            }
            let price = parseFloat(regularPrice);
            let obvy = this.sellerObvyTiers(price, ratio);
            const profit = (price * ratio - obvy).toFixed(2)
            const commission = (price - profit).toFixed(2)
            return {profit, commission}
        }
    }
}
