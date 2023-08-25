import { createToaster } from "@meforma/vue-toaster";

let ToasterAlert = createToaster({
    position: "top-right",
});

export function toaster(type , message) {
    ToasterAlert[type](message);
}

