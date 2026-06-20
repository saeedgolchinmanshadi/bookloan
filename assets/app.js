import "./controllers/csrf_protection_controller.js";
import "./stimulus_bootstrap.js";

import "./styles/vazirmatn.css";
import "bootstrap/dist/css/bootstrap.min.css";

import "tom-select/dist/css/tom-select.bootstrap5.css";
import "./styles/app.css";

import $ from "jquery";
window.$ = window.jQuery = $;

import "bootstrap";
import { Offcanvas } from "bootstrap";

function isSidebarLinkActive(link, route) {
    const exactRoute = link.dataset.activeWhen;
    if (exactRoute) {
        return route === exactRoute;
    }

    const prefix = link.dataset.activeWhenPrefix;
    if (!prefix || !route.startsWith(prefix)) {
        return false;
    }

    const excludedPrefixes = (link.dataset.activeExcludePrefix ?? "")
        .split(",")
        .map((value) => value.trim())
        .filter(Boolean);

    return !excludedPrefixes.some((excluded) => route.startsWith(excluded));
}

function updateSidebarActiveLink() {
    const route = document.querySelector("main.app-main")?.dataset.currentRoute;
    if (!route) {
        return;
    }

    document.querySelectorAll("#appSidebar a.nav-link").forEach((link) => {
        link.classList.toggle("active", isSidebarLinkActive(link, route));
    });
}

function submitDeleteRecord(button) {
    const confirmMessage = button.dataset.deleteConfirm;
    if (confirmMessage && !window.confirm(confirmMessage)) {
        return;
    }

    const form = document.createElement("form");
    form.method = "post";
    form.action = button.dataset.deleteUrl;

    const token = document.createElement("input");
    token.type = "hidden";
    token.name = "_token";
    token.value = button.dataset.deleteToken;
    form.appendChild(token);

    document.body.appendChild(form);
    form.submit();
}

function closeMobileSidebar() {
    if (window.innerWidth >= 992) {
        return;
    }

    const sidebar = document.getElementById("appSidebar");
    if (!sidebar) {
        return;
    }

    Offcanvas.getInstance(sidebar)?.hide();
}

document.addEventListener("click", (event) => {
    const deleteButton = event.target.closest("[data-action='delete-record']");
    if (deleteButton) {
        submitDeleteRecord(deleteButton);
        return;
    }

    const link = event.target.closest(
        "#appSidebar a.nav-link, #appSidebar a.sidebar-brand",
    );
    if (!link) {
        return;
    }

    closeMobileSidebar();
});

document.addEventListener("turbo:load", updateSidebarActiveLink);
document.addEventListener("DOMContentLoaded", updateSidebarActiveLink);

$(document).ready(function () {
    const $borrowDateField = $("#book_loan_borrowDate");
    if ($borrowDateField.length && !$borrowDateField.val()) {
        $borrowDateField
            .siblings(".mt-2")
            .find("button")
            .first()
            .trigger("click");
    }

    $(document).on("input", ".date-mask", function () {
        let $input = $(this);
        let val = $input.val().replace(/\D/g, "");

        let newVal = "";

        if (val.length > 0) {
            let yearStr = val.substring(0, 4);

            if (yearStr.length >= 1 && yearStr[0] !== "1") {
                yearStr = "1";
                val = yearStr + val.substring(1);
            }

            if (
                yearStr.length >= 2 &&
                yearStr[1] !== "4" &&
                yearStr[1] !== "5"
            ) {
                yearStr = yearStr[0] + "4";
                val = yearStr + val.substring(2);
            }

            if (
                yearStr.length >= 3 &&
                yearStr.startsWith("15") &&
                yearStr[2] !== "0"
            ) {
                yearStr = "150";
                val = yearStr + val.substring(3);
            }

            if (yearStr.length === 4) {
                let year = parseInt(yearStr);
                if (year < 1400) yearStr = "1400";
                if (year > 1500) yearStr = "1500";
                val = yearStr + val.substring(4);
            }

            newVal += val.substring(0, 4);
        }

        if (val.length > 4) {
            let monthStr = val.substring(4, 6);
            let m1 = parseInt(monthStr[0]);

            if (monthStr.length === 1 && m1 > 1) {
                monthStr = "0" + m1;
                val = val.substring(0, 4) + monthStr + val.substring(5);
            } else if (monthStr.length === 2) {
                let month = parseInt(monthStr);
                if (month === 0) monthStr = "01";
                if (month > 12) monthStr = "12";
                val = val.substring(0, 4) + monthStr + val.substring(6);
            }

            newVal += "/" + monthStr;
        }

        if (val.length > 6) {
            let dayStr = val.substring(6, 8);
            let d1 = parseInt(dayStr[0]);

            if (dayStr.length === 1 && d1 > 3) {
                dayStr = "0" + d1;
                val = val.substring(0, 6) + dayStr + val.substring(7);
            } else if (dayStr.length === 2) {
                let day = parseInt(dayStr);
                if (day === 0) dayStr = "01";
                if (day > 31) dayStr = "31";
                val = val.substring(0, 6) + dayStr + val.substring(8);
            }

            newVal += "/" + dayStr;
        }

        $input.val(newVal);

        if (newVal.length === 10) {
            let $inputs = $input
                .closest("form")
                .find('input:not([type="hidden"]), select, textarea');
            let nextIndex = $inputs.index(this) + 1;

            if (nextIndex < $inputs.length) {
                $inputs.eq(nextIndex).focus();
            }
        }
    });
});

window.fillDate = function (fieldId, dateString) {
    const $field = $("#" + fieldId);
    if ($field.length) {
        $field.val(dateString);
    }
};
