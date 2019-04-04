package edu.rit.se.a11y.autoresponses.rest;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RestController;

import edu.rit.se.a11y.autoresponses.api.AddSuggestedResponseRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesResponse;
import edu.rit.se.a11y.autoresponses.service.SuggestedResponseService;

@RestController
public class SuggestedResponseController {

    private final SuggestedResponseService suggestedResponseService;

    public SuggestedResponseController(SuggestedResponseService suggestedResponseService) {
        this.suggestedResponseService = suggestedResponseService;
    }

    @PostMapping("/get-suggested-responses")
    public ResponseEntity<GetSuggestedResponsesResponse> getSuggestedResponses(@RequestBody GetSuggestedResponsesRequest getSuggestedResponsesRequest) {
        GetSuggestedResponsesResponse getSuggestedResponsesObject = suggestedResponseService.getSuggestedResponses(getSuggestedResponsesRequest);
        ResponseEntity<GetSuggestedResponsesResponse> responseEntity = new ResponseEntity<GetSuggestedResponsesResponse>(getSuggestedResponsesObject, HttpStatus.OK);
        return responseEntity;
    }

    @PostMapping("/add-suggested-response")
    public ResponseEntity<?> addSuggestedResponse(@RequestBody AddSuggestedResponseRequest addSuggestedResponseRequest) {
        suggestedResponseService.addSuggestedResponse(addSuggestedResponseRequest);
        ResponseEntity<?> responseEntity = new ResponseEntity<>(HttpStatus.OK);
        return responseEntity;
    }

}
