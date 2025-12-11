<?php
// src/Controller/WineQuizController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class WineQuizController extends AbstractController
{
    private array $wineData = [
        'white' => [
            'halbtrocken' => [
                'mild' => [
                    'wine' => 'Müller-Thurgau mit Restsüße',
                                        'origin' => 'Deutschland',
                    'description' => 'Ein milder, leicht süßer Weißwein mit fruchtigen Aromen.'
                ],
                'würzig' => [
                    'wine' => 'Grauburgunder',
                    'description' => 'Würzig und aromatisch mit einer angenehmen Restsüße.'
                ]
            ],
            'trocken' => [
                'säurehaltig' => [
                    'wine' => 'Riesling',
                    'origin' => 'Deutschland',
                    'description' => 'Klassischer deutscher Riesling mit lebendiger Säure.'
                ],
                'fruchtig' => [
                    'wine' => 'Chardonnay',
                    'origin' => 'Frankreich',
                    'description' => 'Elegant und fruchtig mit feiner Struktur.'
                ]
            ]
        ],
        'red' => [
           'halbtrocken' => [
        'sanft' => [
            'wine' => 'Merlot',
            'origin' => 'Italienisch',
            'description' => 'Weicher, fruchtiger Rotwein mit samtigen Tanninen.'
        ],
        'kraeftig' => [
            'wine' => 'Dornfelder',
            'origin' => 'Deutschland',
            'description' => 'Ein kräftiger, halbtrockener Rotwein mit dunklen Fruchtaromen und guter Struktur.'
        ]
            ],
            'trocken' => [
                'würzig' => [
                    'wine' => 'Cabernet',
                    'origin' => 'Bordeaux Wein',
                    'description' => 'Kraftvoller Rotwein mit würzigen Noten und gutem Lagerpotential.'
                ],
                'fruchtig' => [
                    'wine' => 'Pinot Noir',
                    'origin' => 'Burgund',
                    'description' => 'Eleganter Rotwein mit feinen Fruchtaromen und weichen Tanninen.'
                ]
            ]
        ]
    ];
    // Die Map als Eigenschaft der Klasse definieren, um sie wiederzuverwenden
private array $answerLabels = [
    'white' => 'Weiß', // Wegen Ihrer Anforderung, hier 'Rot' statt 'Rotwein'
    'red' => 'Rot',
    'halbtrocken' => 'Halbtrocken',
    'trocken' => 'Trocken',
    'mild' => 'Mild',
    'würzig' => 'Würzig',
    'sanft' => 'Sanft',
    'säurehaltig' => 'Säurehaltig',
    'fruchtig' => 'Fruchtig',
    'kraeftig' => 'Kräftig', // Hinzugefügt, falls benötigt
];

    #[Route('/', name: '/')]
    public function start(SessionInterface $session): Response
    {
        // Session zurücksetzen
        $session->remove('answers');
        
        return $this->render('start.html.twig');
    }

    #[Route('/frage/{step}', name: 'question')]
    public function question(int $step, Request $request, SessionInterface $session): Response
    {
        // Richtige Session-Key verwenden!
        $answers = $session->get('answers', []);

        // Vorherige Antwort speichern
        if ($request->isMethod('POST')) {
            $answer = $request->request->get('answer');
            $answers["step_$step"] = $answer;
            $session->set('answers', $answers);

            // Zur nächsten Frage oder zum Ergebnis
            if ($this->isQuizComplete($answers)) {
                return $this->redirectToRoute('result');
            }
            
            return $this->redirectToRoute('question', ['step' => $step + 1]);
        }

        $questionData = $this->getQuestionForStep($step, $answers);
        
        if (!$questionData) {
            return $this->redirectToRoute('result');
        }

        return $this->render('question.html.twig', [
            'step' => $step,
            'totalSteps' => 3,  // Für das Template
            'question' => $questionData['question'],
            'options' => $questionData['options'],
            'progress' => $this->calculateProgress($step),
            'answers' => $answers, // Die gespeicherten Antworten übergeben
            'answer_labels' => $this->answerLabels
        ]);
    }

    #[Route('/ergebnis', name: 'result')]
    public function result(SessionInterface $session): Response
    {
        $answers = $session->get('answers', []);
        
        if (empty($answers)) {
            return $this->redirectToRoute('/');
        }

        $recommendation = $this->getWineRecommendation($answers);

       // Die Labels für die Summary
        // Diese Map übersetzt die technischen Keys (z.B. 'white') in lesbare Namen (z.B. 'Weißwein')
        $answerLabels = [
            'white' => 'Weißwein',
            'red' => 'Rotwein',
            'halbtrocken' => 'Halbtrocken',
            'trocken' => 'Trocken',
            'mild' => 'Mild',
            'würzig' => 'Würzig',
            'sanft' => 'Sanft',
            'säurehaltig' => 'Säurehaltig',
            'fruchtig' => 'Fruchtig',
        ];

        return $this->render('result.html.twig', [
            'recommendation' => $recommendation,
            'answers' => $answers,
            // Labels für das Template übergeben
            'answer_labels' => $answerLabels, 
            // TotalSteps übergeben, um die Summary-Logik im Template zu vereinfachen
            'totalSteps' => 3 
        ]);
    }

  private function getQuestionForStep(int $step, array $answers): ?array
{
    switch ($step) {
        case 1:
            return [
                'question' => 'Welche Weinfarbe bevorzugen Sie?',
                'options' => [
                    'white' => 'Weißwein',
                    'red' => 'Rotwein'
                ]
            ];
        
        case 2:
            return [
                'question' => 'Welche Geschmacksrichtung bevorzugen Sie?',
                'options' => [
                    'halbtrocken' => 'Halbtrocken',
                    'trocken' => 'Trocken'
                ]
            ];
        
        case 3:
            $color = $answers['step_1'] ?? 'white';
            $dryness = $answers['step_2'] ?? 'halbtrocken';
            
            if ($color === 'white') {
                if ($dryness === 'halbtrocken') {
                    return [
                        'question' => 'Welche Geschmacksnote bevorzugen Sie?',
                        'options' => [
                            'mild' => 'Mild',
                            'würzig' => 'Würzig'
                        ]
                    ];
                } else {
                    return [
                        'question' => 'Welche Charakteristik bevorzugen Sie?',
                        'options' => [
                            'säurehaltig' => 'Säurehaltig',
                            'fruchtig' => 'Fruchtig'
                        ]
                    ];
                }
            } else { // rot
                if ($dryness === 'halbtrocken') {
                    return [
                        'question' => 'Welche Rebsorte interessiert Sie?',
                        'options' => [
                            'sanft' => 'Sanft',
                            'kraeftig' => 'Kräftig'
                        ]
                    ];
                } else {
                    return [
                        'question' => 'Welchen Charakter bevorzugen Sie?',
                        'options' => [
                            'würzig' => 'Würzig',
                            'fruchtig' => 'Fruchtig'
                        ]
                    ];
                }
            }
        
        default:
            return null;
    }
}

    private function getWineRecommendation(array $answers): array
{
    $color = $answers['step_1'] ?? 'white';
    $dryness = $answers['step_2'] ?? 'halbtrocken';
    $characteristic = $answers['step_3'] ?? 'mild';

    $wine = $this->wineData[$color][$dryness][$characteristic] ?? null;

    if (!$wine) {
        return [
            'wine' => 'Empfehlung nicht verfügbar',
            'description' => 'Basierend auf Ihren Antworten konnten wir keine passende Empfehlung finden.',
            'origin' => ''
        ];
    }

    return $wine;
}

    private function isQuizComplete(array $answers): bool
    {
        return count($answers) >= 3;
    }

    private function calculateProgress(int $step): int
    {
        return min(100, ($step / 3) * 100);
    }
}