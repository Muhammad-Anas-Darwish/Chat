<script setup>
const props = defineProps({
    contactId: String,
});

axios.delete(route('contacts.delete', props.contactId))
    .then(res => {
        console.log(res);
    })
    .catch(error => {
        console.log(error);
});
</script>
