<?php

namespace App\Service;

class Messages
{
    // PRESTATAIRE 10
    CONST PRESTATAIRE_NOT_EXIST = "Le prestataire sélectionné n'a pas été trouvé. Veuillez entrer le bon matricule";
    CONST PRESTATAIRE_NOT_IT = "Cette demande de prestation ne vous a pas été adressée.";
    CONST PRESTATAIRE_NOT_YOU = "Vous n'êtes pas le prestataire concerné par cette discussion.";

    // DEMANDEUR 20
    CONST DEMANDEUR_PROFILE_CANNOT_HIRE = "Vous devez avoir un profile demandeur de prestation pour pouvoir embaucher ce prestataire";
    CONST DEMANDEUR_NOT_YOU = "Vous n'êtes pas le demandeur concerné par cette discussion.";
    CONST DEMANDEUR_NOT_EXIST = "Le demandeur sélectionné n'a pas été trouvé. Veuillez entrer le bon code";

    /**
     * Les messages relatifs au projet
     */
    CONST PROJET_DEMANDE_EMBAUCHE = "Votre demande d'embauche a été envoyée avec succès!";
    CONST PROJET_DEMANDE_NON_DISPONIBLE = "La demande de prestation n'est plus disponible. Si vous pensez que c'est une erreur veuillez contacter le demandeur ou les administrateurs de la plateforme";
    CONST PROJET_DEMANDE_SUPPRIME = "La suppression de votre demande de prestation a été effectuée avec succès!";

    /**
     * Les messages relatifs a la candidature
     */
    CONST CANDIDATURE_REPONSE_DEMANDE = "Votre reponse a été envoyée avec succès!";
    // LOCALITE 50
    // COMPETENCE 60

    CONST EMETTEUR_DEMANDEUR = "DEMANDEUR";
    CONST EMETTEUR_PRESTATAIRE = "PRESTATAIRE";
}