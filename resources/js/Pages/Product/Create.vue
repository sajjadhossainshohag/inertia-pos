<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import Nav from '../Components/Nav.vue';

const form = useForm({
    name: null,
    sku: null,
    thumbnail: null,
    unit: null,
    unitValue: null,
    sellingPrice: null,
    purchasePrice: null,
    discount: null,
    tax: null,
    variations: [
        { attributes: [{ key: null, value: null }], purchasePrice: null, sellPrice: null },
    ],
});

const addVariation = () => {
    form.variations.push({ attributes: [{ key: null, value: null }], purchasePrice: null, sellPrice: null });
};

const addAttribute = (variationIndex) => {
    form.variations[variationIndex].attributes.push({ key: null, value: null });
};

const removeAttribute = (variationIndex, attributeIndex) => {
    form.variations[variationIndex].attributes.splice(attributeIndex, 1);
};

const removeVariation = (index) => {
    form.variations.splice(index, 1);
};
</script>

<template>

    <Head title="Create Product" />
    <Nav />

    <div class="container">
        <form @submit.prevent="form.post(route('products.store'))" enctype="multipart/form-data">
            <div class="row mt-4">
                <div class="col-xxl-12 bg-primary p-2 text-center text-white mb-4">Product Info</div>
                <div class="col-xxl-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.name }"
                            v-model="form.name" id="name">
                        <div class="text-danger" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.sku }"
                            v-model="form.sku" id="sku">
                        <div class="text-danger" v-if="form.errors.sku">
                            {{ form.errors.sku }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-12">
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail</label>
                        <input type="file" class="form-control" :class="{ 'is-invalid': form.errors.thumbnail }"
                            @input="form.thumbnail = $event.target.files[0]" id="thumbnail">
                        <div class="text-danger" v-if="form.errors.thumbnail">
                            {{ form.errors.thumbnail }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.unit }"
                            v-model="form.unit" id="unit">
                        <div class="text-danger" v-if="form.errors.unit">
                            {{ form.errors.unit }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit Value</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.unitValue }"
                            v-model="form.unitValue" id="unit">
                        <div class="text-danger" v-if="form.errors.unitValue">
                            {{ form.errors.unitValue }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="mb-3">
                        <label for="sellingPrice" class="form-label">Selling Price</label>
                        <input type="number" class="form-control" :class="{ 'is-invalid': form.errors.sellingPrice }"
                            v-model="form.sellingPrice" id="sellingPrice">
                        <div class="text-danger" v-if="form.errors.sellingPrice">
                            {{ form.errors.sellingPrice }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="mb-3">
                        <label for="purchasePrice" class="form-label">Purchase Price</label>
                        <input type="number" class="form-control" :class="{ 'is-invalid': form.errors.purchasePrice }"
                            v-model="form.purchasePrice" id="purchasePrice">
                        <div class="text-danger" v-if="form.errors.purchasePrice">
                            {{ form.errors.purchasePrice }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="mb-3">
                        <label for="purchasePrice" class="form-label">Discount</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" :class="{ 'is-invalid': form.errors.discount }"
                                v-model="form.discount">
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="text-danger" v-if="form.errors.discount">
                            {{ form.errors.discount }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="mb-3">
                        <label for="purchasePrice" class="form-label">Tax</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" :class="{ 'is-invalid': form.errors.tax }"
                                v-model="form.tax">
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="text-danger" v-if="form.errors.tax">
                            {{ form.errors.tax }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12 bg-primary p-2 text-center text-white mb-4">Product Attributes</div>
            <div>
                <h5>Variations</h5>
                <div v-for="(variation, index) in form.variations" :key="index" class="border p-3 mb-3">
                    <div v-for="(attribute, attrIndex) in variation.attributes" :key="`attr-${attrIndex}`"
                        class="row mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control" v-model="attribute.key"
                                placeholder="Attribute (e.g., Color)" />
                            <div class="text-danger"
                                v-if="form.errors[`variations.${index}.attributes.${attrIndex}.key`]">
                                {{ form.errors[`variations.${index}.attributes.${attrIndex}.key`] }}
                            </div>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" v-model="attribute.value"
                                placeholder="Value (e.g., Red)" />
                            <div class="text-danger"
                                v-if="form.errors[`variations.${index}.attributes.${attrIndex}.value`]">
                                {{ form.errors[`variations.${index}.attributes.${attrIndex}.value`] }}
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-danger btn-sm mt-2" type="button"
                                @click="removeAttribute(index, attrIndex)">
                                Remove Attribute
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-secondary btn-sm" type="button" @click="addAttribute(index)">
                        Add Attribute
                    </button>

                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="purchasePrice" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control" v-model="variation.purchasePrice" />
                            <div class="text-danger" v-if="form.errors[`variations.${index}.purchasePrice`]">
                                {{ form.errors[`variations.${index}.purchasePrice`] }}
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="sellPrice" class="form-label">Sell Price</label>
                            <input type="number" class="form-control" v-model="variation.sellPrice" />
                            <div class="text-danger" v-if="form.errors[`variations.${index}.sellPrice`]">
                                {{ form.errors[`variations.${index}.sellPrice`] }}
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-danger mt-3" type="button" @click="removeVariation(index)">
                        Remove Variation
                    </button>
                </div>

                <button class="btn btn-warning mt-3" type="button" @click="addVariation">
                    Add Variation
                </button>
            </div>

            <div class="text-center">
                <button class="btn btn-success my-4" type="submit" :disabled="form.processing">
                    <span v-if="!form.processing">Create Product</span>
                    <span v-if="form.processing">
                        Creating...
                        <span v-if="form.progress">({{ form.progress.percentage }}%)</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</template>