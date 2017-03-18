<template>
    <form action="/purchases" method="POST">
        <input type="hidden" name="stripeToken" v-model="stripeToken">
        <input type="hidden" name="stripeEmail" v-model="stripeEmail">

        <select name="product" v-model="product">
            <option v-for="product in products" :value="product.id">
                {{ product.name }} -- ${{ product.price / 100 }}
            </option>
        </select>

        <button type="submit" @click.prevent="buy">Buy My Book</button>
    </form>
</template>

<script>
    export default {
        props: ['products'],

        data() {
            return {
                stripeEmail: '',
                stripeToken: '',
                product: 1
            };
        },

        created() {
            this.stripe = StripeCheckout.configure({
                key: StripeDemo.stripeKey,
                image: "https://stripe.com/img/documentation/checkout/marketplace.png",
                locale: "auto",
                panelLabel: "Subscribe For",
                token: (token) => {
                    this.stripeToken = token.id;
                    this.stripeEmail = token.email;

                    this.$http.post('/purchases', this.$data)
                        .then(response => alert('Complete! Thanks for your payment!'));
                }
            });
        },

        methods: {
            buy() {
                let product = this.findProductById(this.product);

                this.stripe.open({
                    name: product.name,
                    description: product.description,
                    zipCode: true,
                    amount: product.price
                }) ;
            },

            findProductById(id) {
                return this.products.find(product => product.id == id);
            }
        }
    }
</script>
