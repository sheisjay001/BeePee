document.addEventListener('DOMContentLoaded', function() {
    // Check if user has seen tour
    if (!localStorage.getItem('tour_seen')) {
        startTour();
    }
});

function startTour() {
    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            cancelIcon: {
                enabled: true
            },
            classes: 'shadow-md bg-purple-dark',
            scrollTo: { behavior: 'smooth', block: 'center' }
        }
    });

    tour.addStep({
        id: 'welcome',
        text: 'Welcome to BeePee! Let\'s take a quick tour of your new Health Dashboard.',
        attachTo: {
            element: 'h1',
            on: 'bottom'
        },
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel
            },
            {
                text: 'Next',
                action: tour.next
            }
        ]
    });

    tour.addStep({
        id: 'stats',
        text: 'Here you can see your key health metrics at a glance, including BP, Sugar, Weight, BMI, and your current Streak!',
        attachTo: {
            element: '#statsCards',
            on: 'bottom'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back
            },
            {
                text: 'Next',
                action: tour.next
            }
        ]
    });

    tour.addStep({
        id: 'chart',
        text: 'Visualize your health trends over time. Interactive charts help you spot patterns.',
        attachTo: {
            element: '#healthChart',
            on: 'top'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back
            },
            {
                text: 'Next',
                action: tour.next
            }
        ]
    });

    tour.addStep({
        id: 'form',
        text: 'Record your daily measurements here. It takes just a few seconds!',
        attachTo: {
            element: '#trackerFormContainer',
            on: 'left'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back
            },
            {
                text: 'Next',
                action: tour.next
            }
        ]
    });

    tour.addStep({
        id: 'export',
        text: 'Need to share with your doctor? Print a report or export your data as CSV.',
        attachTo: {
            element: '.text-lg.leading-6.font-medium.text-gray-900', // Heading "Recent Logs"
            on: 'bottom'
        },
        buttons: [
            {
                text: 'Done',
                action: () => {
                    localStorage.setItem('tour_seen', 'true');
                    tour.complete();
                    Toastify({ text: "You're all set!", backgroundColor: "#059669" }).showToast();
                }
            }
        ]
    });

    tour.start();
}