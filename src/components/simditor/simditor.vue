<template>
    <div class="simditor">
        <textarea id="simditor"></textarea>
    </div>
</template>
<script>
import $ from "jquery";
import Simditor from "simditor";

export default {
  name: "simditor",
  props: {
    value: String
  },
  data() {
    return {
      simditor: null
    };
  },
  methods: {},
  computed: {},
  mounted() {
    this.simditor = new Simditor({
      textarea: "#simditor",
      upload: false,
      tabIndent: true,
      toolbar: [
        "title",
        "bold",
        "italic",
        "underline",
        "strikethrough",
        "fontScale",
        "color",
        "ol",
        "ul",
        "blockquote",
        "code",
        "table",
        "link",
        "image",
        "hr",
        "indent",
        "outdent",
        "alignment"
      ]
    });
    this.$nextTick(() => {
      this.simditor.setValue(this.value);
    });

    this.simditor.on("valuechanged", () => {
      var str = this.simditor.getValue();
      this.$emit("input", str);
    });
    // this.$emit("input:value", newValue);
  },
  components: {},
  watch: {
    value() {}
  }
};
</script>
<style lang="scss" scoped>
@import "simditor.scss";
</style>

