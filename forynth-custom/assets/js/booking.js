
(function () {
    "use strict";

    // Yalnızca rezervasyon sayfasında çalışır.
    const translations = new Map([
        ["THANK YOU FOR BOOKING!", "RANDEVU TALEBİNİZ ALINDI!"],
        ["Price", "Ücret"],
        ["Personal information", "Kişisel Bilgiler"],
        ["Booking overview", "Rezervasyon Özeti"],
        ["Book New Appointment", "Yeni Randevu Oluştur"],
        ["Add to Google Calendar", "Google Takvim'e Ekle"],
        ["Date & time", "Tarih ve Saat"],
        ["Fields with * are required",
         "* ile işaretli alanların doldurulması zorunludur."]
    ]);

    const months = {
        January: "Ocak",
        February: "Şubat",
        March: "Mart",
        April: "Nisan",
        May: "Mayıs",
        June: "Haziran",
        July: "Temmuz",
        August: "Ağustos",
        September: "Eylül",
        October: "Ekim",
        November: "Kasım",
        December: "Aralık"
    };

    const days = {
        Mo: "Pzt",
        Tu: "Sal",
        We: "Çar",
        Th: "Per",
        Fr: "Cum",
        Sa: "Cmt",
        Su: "Paz"
    };

    const fullDays = {
        Monday: "Pazartesi",
        Tuesday: "Salı",
        Wednesday: "Çarşamba",
        Thursday: "Perşembe",
        Friday: "Cuma",
        Saturday: "Cumartesi",
        Sunday: "Pazar"
    };

    function translateText(text, inCalendar, inTitle, inDay) {
        const value = text.trim().replace(/\s+/g, " ");

        if (translations.has(value)) {
            return text.replace(value, translations.get(value));
        }

        if (inCalendar && inDay && days[value]) {
            return text.replace(value, days[value]);
        }

        if (inCalendar && inTitle) {
            for (const [en, tr] of Object.entries(months)) {
                if (value.includes(en)) {
                    return text.replace(en, tr);
                }
            }
        }

        return text;
    }

    function translatePage() {
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT
        );

        const changes = [];
        let node;

        while ((node = walker.nextNode())) {
            const parent = node.parentElement;

            if (!parent || parent.closest(
                "script, style, textarea, code, option"
            )) {
                continue;
            }

            const calendar = parent.closest(".ui-datepicker");
            const title = parent.closest(".ui-datepicker-title");
            const day = parent.closest(
                ".ui-datepicker-calendar th"
            );

            const updated = translateText(
                node.nodeValue,
                Boolean(calendar),
                Boolean(title),
                Boolean(day)
            );

            if (updated !== node.nodeValue) {
                changes.push([node, updated]);
            }
        }

        changes.forEach(([textNode, value]) => {
            textNode.nodeValue = value;
        });

        // Gün adlarının erişilebilirlik açıklamaları.
        document.querySelectorAll(
            ".ui-datepicker-calendar th [title]"
        ).forEach(element => {
            const current = element.getAttribute("title");

            if (fullDays[current]) {
                element.setAttribute("title", fullDays[current]);
            }
        });
    }

    function initialize() {
        let scheduled = false;

        function scheduleTranslation() {
            if (scheduled) return;
            scheduled = true;

            requestAnimationFrame(() => {
                scheduled = false;
                translatePage();
            });
        }

        translatePage();

        const observer = new MutationObserver(scheduleTranslation);

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initialize);
    } else {
        initialize();
    }
})();
