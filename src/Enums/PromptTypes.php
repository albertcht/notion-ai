<?php

namespace AlbertCht\NotionAi\Enums;

use AlbertCht\NotionAi\Enums\BaseEnum;

class PromptTypes extends BaseEnum
{
    const HELP_ME_WRITE = 'helpMeWrite';
    const HELP_ME_EDIT = 'helpMeEdit';
    const HELP_ME_DRAFT = 'helpMeDraft';
    const CONTINUE_WRITING = 'continueWriting';
    const CHANGE_TONE = 'changeTone';
    const SUMMARIZE = 'summarize';
    const IMPROVE_WRITING = 'improveWriting';
    const FIX_SPELLING_GRAMMAR = 'fixSpellingGrammar';
    const TRANSLATE = 'translate';
    const EXPLAIN_THIS = 'explainThis';
    const MAKE_LONGER = 'makeLonger';
    const MAKE_SHORTER = 'makeShorter';
    const FIND_ACTION_ITEMS = 'findActionItems';
    const SIMPLIFY_LANGUAGE = 'simplifyLanguage';
}
