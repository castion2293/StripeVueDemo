<template>
    <form action="/subscription" method="POST">
        <input type="hidden" name="stripeToken" v-model="stripeToken">
        <input type="hidden" name="stripeEmail" v-model="stripeEmail">

        <select name="plan" v-model="plan">
            <option v-for="plan in plans" :value="plan.id">
                {{ plan.name }} -- ${{ plan.price / 100 }}
            </option>
        </select>

        <button type="submit" @click.prevent="subscribe">Subscribe</button>
        <p class="help is-danger" v-show="status" v-text="status"></p>
    </form>
</template>

<script>
    export default {
        props: ['plans'],

        data() {
            return {
                stripeEmail: '',
                stripeToken: '',
                plan: 1,
                status: false
            };
        },

        created() {
            this.stripe = StripeCheckout.configure({
                key: StripeDemo.stripeKey,
                image: "https://stripe.com/img/documentation/checkout/marketplace.png",
                locale: "auto",
                panelLabel: "Subscribe For",
                email: StripeDemo.user.email,
                token: (token) => {
                    this.stripeToken = token.id;
                    this.stripeEmail = token.email;

                    this.$http.post('/subscription', this.$data)
                        .then(response => {
                            if (response.status == 200)
                                alert('payment is successful!')
                        })
                        .catch(response => {
                            this.status = response.body.status
                        })
                }
            });
        },

        methods: {
            subscribe() {
                let plan = this.findPlanById(this.plan);

                this.stripe.open({
                    name: plan.name,
                    description: plan.name,
                    zipCode: true,
                    amount: plan.price
                }) ;
            },

            findPlanById(id) {
                return this.plans.find(plan => plan.id == id);
            }
        }
    }
</script>
